<?php

namespace TransactionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TransactionBundle\Entity\TransactionXML;
use Aws\S3\S3Client;

class SendTransactionCommand extends ContainerAwareCommand {
    protected function configure() {
        $this->setName('send:transaction')->setDescription('Envoie des transaction a la banque');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('Debut EDI');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $serializer = $this->getContainer()->get('jms_serializer');
        $root = $this->getContainer()->get('kernel')->getRootDir();
        $transactions = $em->getRepository('TransactionBundle:Transaction')->findAll();
        $dateDuJour = new \DateTime();
        $dateDuJour = $dateDuJour->format('Y-m-d');
        $transationsDuJour = [];
        foreach ($transactions as $transaction) {
            $date = $transaction->getDate();
            $date = $date->format('Y-m-d');
            if ($date == $dateDuJour) {
                $transationsDuJour[] = $transaction;
            }
        }
        $transactionsXML = [];
        foreach ($transationsDuJour as $transaction) {
            $transactionXML = new TransactionXML();
            $transactionXML->setId($transaction->getId());
            $transactionXML->setDate($transaction->getDate());
            $transactionXML->setMontant($transaction->getMontant());
            $transactionXML->setIdCommercant($transaction->getLecteur()->getCommercant()->getId());
            $transactionXML->setNomCommercant($transaction->getLecteur()->getCommercant()->getLibelle());
            $transactionXML->setIbanCommercant($transaction->getLecteur()->getCommercant()->getIban());
            $transactionsXML[] = $transactionXML;
        }
        $xml = $serializer->serialize($transactionsXML, 'xml');
        $keyContent = file_get_contents($root . '/AWS_ACCESS_KEY_ID.txt');
        $secretContent = file_get_contents($root . '/AWS_SECRET_ACCESS_KEY.txt');
        $bucket = getenv('S3_BUCKET_NAME') ? getenv('S3_BUCKET_NAME') : 'dwarse';
        $key = getenv('AWS_ACCESS_KEY_ID') ? getenv('AWS_ACCESS_KEY_ID') : $keyContent;
        $secret = getenv('AWS_SECRET_ACCESS_KEY') ? getenv('AWS_SECRET_ACCESS_KEY') : $secretContent;
        $s3 = S3Client::factory(['key' => $key, 'secret' => $secret]);
        $s3->upload($bucket, 'transactions/decrypted/' . $dateDuJour . '.xml', $xml, 'public-read');
        $token = $this->generateToken(64);
        $transactionsXML = [];
        foreach ($transationsDuJour as $transaction) {
            $transactionXML = new TransactionXML();
            $transactionXML->setId($this->crypter($token, strval($transaction->getId())));
            $transactionXML->setDate($this->crypter($token, $transaction->getDate()->format('Y-m-d H:i')));
            $transactionXML->setMontant($this->crypter($token, strval($transaction->getMontant())));
            $transactionXML->setIdCommercant($this->crypter($token, strval($transaction->getLecteur()->getCommercant()->getId())));
            $transactionXML->setNomCommercant($this->crypter($token, $transaction->getLecteur()->getCommercant()->getLibelle()));
            $transactionXML->setIbanCommercant($this->crypter($token, $transaction->getLecteur()->getCommercant()->getIban()));
            $transactionsXML[] = $transactionXML;
        }
        $xml = $serializer->serialize($transactionsXML, 'xml');
        $s3->upload($bucket, 'transactions/crypted/' . $dateDuJour . '.xml', $xml, 'public-read');
        $url = 'http://dwarsebanque.herokuapp.com/transactions';
        //        $url = 'http://dwarse.bank/transactions';
        $headers = ['Content-Type' => 'application/xml', 'Accept' => 'application/json', 'Token' => $token];
        $response = \Requests::post($url, $headers, $xml);
        $responseJson = json_decode($response->body);
        foreach ($responseJson as $item) {
            $commercant = $em->getRepository('CommercantBundle:Commercant')->find($item->id);
            $lecteur = $commercant->getLecteur();
            $lecteur->setSolde($lecteur->getSolde() + $item->solde);
            $em->persist($lecteur);
        }
        $em->flush();
        $output->writeln('Fin EDI');
    }

    private function crypter($token, $data) {
        $maCleDeCryptage = md5($token);
        $letter = -1;
        $newstr = "";
        $strlen = strlen($data);
        for ($i = 0; $i < $strlen; $i++) {
            $letter++;
            if ($letter > 31) {
                $letter = 0;
            }
            $neword = ord($data{$i}) + ord($maCleDeCryptage{$letter});
            if ($neword > 255) {
                $neword -= 256;
            }
            $newstr .= chr($neword);
        }
        return base64_encode($newstr);
    }


    private function generateToken($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }

}
