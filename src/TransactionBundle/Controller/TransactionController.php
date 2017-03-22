<?php
namespace TransactionBundle\Controller;

use Aws\S3\S3Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use TransactionBundle\Entity\Transaction;
use TransactionBundle\Entity\VerifTransaction;
use TransactionBundle\Form\VerifTransactionType;

class TransactionController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Post("/paiement")
     */
    public function PaiementAction(Request $request) {
        $verifTransaction = new VerifTransaction();
        $form = $this->createForm(VerifTransactionType::class, $verifTransaction);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $token = $request->query->get("token");
        $em = $this->get('doctrine.orm.entity_manager');
        $authToken = $em->getRepository('AuthBundle:AuthToken')->findOneByValue($token);
        $user = $authToken->getUser();
        $commercant = $user->getCommercant();
        $lecteur = $commercant->getLecteur();
        if ($lecteur == null) {
            return View::create(['message' => 'Vous n\'avez pas de lecteur'], Response::HTTP_BAD_REQUEST);
        }
        $carte = $em->getRepository("EmployeBundle:Carte")->findOneByNumero($verifTransaction->getNumeroCarte());
        if ($carte == null) {
            return View::create(['message' => 'Carte introuvable'], Response::HTTP_BAD_REQUEST);
        }
        $encoder = $this->get('security.password_encoder');
        $isPinValid = $encoder->isPasswordValid($carte, $verifTransaction->getPin());
        if (!$isPinValid) {
            return View::create(['message' => 'Votre code pin est incorrecte'], Response::HTTP_BAD_REQUEST);
        }
        $opposed = $carte->getOpposed();
        if ($opposed) {
            return View::create(['message' => 'Votre carte est désactivée, contactez votre employeur'], Response::HTTP_BAD_REQUEST);
        }
        $solde = $carte->getSolde();
        if ($solde < $verifTransaction->getMontant()) {
            return View::create(['message' => 'Votre solde est insuffisant'], Response::HTTP_BAD_REQUEST);
        }
        $transaction = new Transaction();
        $transaction->setDate(new \DateTime('now'));
        $transaction->setMontant($verifTransaction->getMontant());
        $transaction->setCarte($carte);
        $transaction->setLecteur($lecteur);
        $transaction->setAvenir(true);
        $em->persist($transaction);
        $nouveauSolde = $solde - $verifTransaction->getMontant();
        $carte->setSolde($nouveauSolde);
        $em->persist($carte);
        $em->flush();
        $messageCommercant = \Swift_Message::newInstance();
        $logoImgUrl = $messageCommercant->embed(\Swift_Image::fromPath('https://s3.amazonaws.com/dwarse/assets/img/logo.png'));
        $heartImgUrl = $messageCommercant->embed(\Swift_Image::fromPath('https://s3.amazonaws.com/dwarse/assets/img/heart.png'));
        $nameCommercant = $lecteur->getCommercant()->getCivilite() . ' ' . $lecteur->getCommercant()
                ->getNom() . ' ' . $lecteur->getCommercant()->getPrenom();
        $messageCommercant->setSubject('Nouvelle transaction Aventix')
            ->setFrom(array('dwarse.development@gmail.com' => 'Dwarse Team'))->setTo($lecteur->getCommercant()
                ->getUser()->getEmail())->setCharset('utf-8')->setContentType('text/html')
            ->setBody($this->renderView('@Transaction/Emails/post_transaction_commercant.html.twig', ['logoImgUrl' => $logoImgUrl,
                'heartImgUrl' => $heartImgUrl, 'name' => $nameCommercant,
                'date' => $transaction->getDate()->format('d/m/Y'), 'heure' => $transaction->getDate()->format('H:i'),
                'montant' => $transaction->getMontant()]));
        $this->get('mailer')->send($messageCommercant);
        $root = $this->get('kernel')->getRootDir();
        $keyContent = file_get_contents($root . '/AWS_ACCESS_KEY_ID.txt');
        $secretContent = file_get_contents($root . '/AWS_SECRET_ACCESS_KEY.txt');
        $bucket = getenv('S3_BUCKET_NAME') ? getenv('S3_BUCKET_NAME') : 'dwarse';
        $key = getenv('AWS_ACCESS_KEY_ID') ? getenv('AWS_ACCESS_KEY_ID') : $keyContent;
        $secret = getenv('AWS_SECRET_ACCESS_KEY') ? getenv('AWS_SECRET_ACCESS_KEY') : $secretContent;
        $s3 = S3Client::factory(['key' => $key, 'secret' => $secret]);
        $factureNum = time() . mt_rand();
        $datet = new \DateTime();
        $content = $this->get('templating')
            ->render('TransactionBundle:Pdfs:post_transaction_employe.html.twig', ['numFacture' => $factureNum,
                'date' => $datet, 'nom' => $carte->getEmploye()->getNom(),
                'prenom' => $carte->getEmploye()->getPrenom(), 'adresse' => $carte->getEmploye()->getEmployeur()->getAdresse(),
                'telephone' => $carte->getEmploye()->getNumTel(),
                'email' => $carte->getEmploye()->getUser()->getEmail(), 'transaction' => $transaction]);
        $html2pdf = new \HTML2PDF('P', 'A4', 'fr');
        $html2pdf->WriteHTML($content);
        $content_PDF = $html2pdf->Output('', true);
        $pdf = $s3->upload($bucket, 'pdfs/employes/' . $carte->getEmploye()
                ->getId() . '/' . $transaction->getId() . '.pdf', $content_PDF, 'public-read');
        $link = $pdf->get('ObjectURL');
        $messageEmploye = \Swift_Message::newInstance();
        $logoImgUrl = $messageEmploye->embed(\Swift_Image::fromPath('https://s3.amazonaws.com/dwarse/assets/img/logo.png'));
        $heartImgUrl = $messageEmploye->embed(\Swift_Image::fromPath('https://s3.amazonaws.com/dwarse/assets/img/heart.png'));
        $nameEmploye = $carte->getEmploye()->getCivilite() . ' ' . $carte->getEmploye()
                ->getNom() . ' ' . $carte->getEmploye()->getPrenom();
        $messageEmploye->setSubject('Nouvelle transaction Aventix')
            ->setFrom(array('dwarse.development@gmail.com' => 'Dwarse Team'))->setTo($carte->getEmploye()->getUser()
                ->getEmail())->setCharset('utf-8')->setContentType('text/html')
            ->setBody($this->renderView('@Transaction/Emails/post_transaction_employe.html.twig', ['logoImgUrl' => $logoImgUrl,
                'heartImgUrl' => $heartImgUrl, 'name' => $nameEmploye,
                'date' => $transaction->getDate()->format('d/m/Y'), 'heure' => $transaction->getDate()->format('H:i'),
                'montant' => $transaction->getMontant()]))->attach(\Swift_Attachment::fromPath($link));;
        $this->get('mailer')->send($messageEmploye);
        return ["Success" => "La transaction à bien été effectuée"];
    }
}