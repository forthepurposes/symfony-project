<?php
namespace App\Tests\Controller;

use App\Controller\InvoiceController;
use App\Entity\Invoice;
use App\Formatter\ApiResponseFormatter;
use App\Repository\InvoiceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoiceControllerTest extends TestCase {

    // Test index with data
    public function testIndex() {
        $invoice = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()->getMock();
        $invoice->method('toArray')->willReturn(['id' => 1, 'name' => 'Invoice One']);

        $repo = $this->getMockBuilder(InvoiceRepository::class)->disableOriginalConstructor()
            ->getMock();
        $repo->method('findAll')->willReturn([$invoice]);

        $formatter = $this->getMockBuilder(ApiResponseFormatter::class)
            ->disableOriginalConstructor()->getMock();
        $formatter->method('withData')->with([['id' => 1, 'name' => 'Invoice One']])->willReturnSelf();
        $formatter->method('response')->willReturn(new JsonResponse(['data' => [['id' => 1, 'name' => 'Invoice One']]]));

        $em = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()
            ->getMock();
        $uRepo = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()->getMock();

        $ctrl = new InvoiceController($formatter, $em, $repo, $uRepo);

        $resp = $ctrl->index();
        $this->assertInstanceOf(JsonResponse::class, $resp);
    }

    public function testEmptyIndex() {
        $r = $this->getMockBuilder(InvoiceRepository::class)->disableOriginalConstructor()
            ->getMock();
        $r->method('findAll')
            ->willReturn([]);

        $fmt = $this->getMockBuilder(ApiResponseFormatter::class)->disableOriginalConstructor()->getMock();
        $fmt->method('withData')->willReturnSelf();
        $fmt->method('response')->willReturn(new JsonResponse(['data' => []]));

        $em = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()
            ->getMock();

        $userRepo = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()->getMock();

        $controller = new InvoiceController($fmt, $em, $r, $userRepo);
        $result = $controller

            ->index();

        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function testGetById() {
        $invObj = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $invObj->method('toArray')->willReturn(['id' => 2, 'name' => 'Invoice Two']);

        $ir = $this->getMockBuilder(InvoiceRepository::class)->disableOriginalConstructor()
            ->getMock();
        $ir->method('findOneBy')->with(['id' => 2])->willReturn($invObj);

        $formatter = $this->getMockBuilder(ApiResponseFormatter::class)->disableOriginalConstructor()->getMock();
        $formatter->method('withData')->with(['id' => 2, 'name' => 'Invoice Two'])->willReturnSelf();
        $formatter->method('response')->willReturn(new JsonResponse(['data' => ['id' => 2, 'name' => 'Invoice Two']]));

        $em = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()
            ->getMock();

        $uR = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()->getMock();

        $ctrl = new InvoiceController($formatter, $em, $ir, $uR);
        $res = $ctrl->getInvoiceById(2);
        $this->assertInstanceOf(JsonResponse::class, $res);
    }

    public function testCreateEmpty() {
        $req = new Request([], [], [], [], [], [], '');


        $fmt = $this->getMockBuilder(ApiResponseFormatter::class)
            ->disableOriginalConstructor()->getMock();
        $fmt->method('withMessage')->willReturnSelf();
        $fmt->method('withStatus')->with(Response::HTTP_BAD_REQUEST)->willReturnSelf();
        $fmt->method('response')->willReturn(new JsonResponse(['msg' => 'Invalid request'], Response::HTTP_BAD_REQUEST));

        $em = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()
            ->getMock();

        $repo = $this->getMockBuilder(InvoiceRepository::class)
            ->disableOriginalConstructor()->getMock();

        $uRepo = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()->getMock();

        $contr = new InvoiceController($fmt, $em, $repo, $uRepo);
        $r = $contr->create($req);
        $this->assertInstanceOf(JsonResponse::class, $r);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $r->getStatusCode());
    }

    public function testCreateValid() {
        $data = [
            'user_id' => 1,
            'name' => 'CoName',
            'street' => 'StreetX',
            'street_number' => '101',
            'street_flat_number' => 'B',
            'city' => 'Metropolis',
            'post_code' => '12345',
            'tax_number' => 'TAX001',
            'phone' => '999-9999',
            'email' => 'contact@coname.com'
        ];
        $request = new Request([], [], [], [], [], [], json_encode($data));

        $uR = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()
            ->getMock();
        $uR->method('findOneBy')->with(['id' => 1])->willReturn(new \stdClass());

        $emObj = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()->getMock();
        $emObj->expects($this->once())->method('persist');
        $emObj->expects($this->once())->method('flush');

        $fmt = $this->getMockBuilder(ApiResponseFormatter::class)
            ->disableOriginalConstructor()->getMock();
        $fmt->method('withData')->willReturnSelf();
        $fmt->method('response')->willReturn(new JsonResponse(['data' => $data]));

        $invRepo = $this->getMockBuilder(InvoiceRepository::class)->disableOriginalConstructor()
            ->getMock();

        $controller = new InvoiceController($fmt, $emObj, $invRepo, $uR);

        $res = $controller->create($request);
        $this->assertInstanceOf(JsonResponse::class, $res);
    }
}
