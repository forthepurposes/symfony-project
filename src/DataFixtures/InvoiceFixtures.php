<?php

namespace App\DataFixtures;

use App\Entity\Invoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InvoiceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $invoice = new Invoice();
        $invoice->setCompanyName('My Comapny');
        $invoice->setCompanyStreet('My Street');
        $invoice->setCompanyStreetNumber('2');
        $invoice->setCompanyStreetFlatNumber('22D');
        $invoice->setCompanyCity('Poznań');
        $invoice->setCompanyPostCode('80-460');
        $invoice->setCreated(new \DateTime());
        $invoice->setUpdated(new \DateTime());
        $invoice->setEmail('karol.bojski@cdv.pl');
        $invoice->setPhone('123123123');
        $invoice->setTaxNumber('1234567890');

        $manager->persist($invoice);
        $manager->flush();
    }
}
