<?php
namespace RaffleToolsTests\Domain\Client;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Equip\Adr\PayloadInterface;
use Equip\Payload;
use RaffleTools\Domain\Client\Form\Patch;
use RaffleTools\Domain\Client\PatchClient;
use RaffleTools\Entity\Client;

class PatchClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var PatchClient */
    private $patchClient;

    /** @var Patch|\PHPUnit_Framework_MockObject_MockObject */
    private $form;

    /** @var DoctrineObject|\PHPUnit_Framework_MockObject_MockObject */
    private $doctrineObject;

    /** @var EntityManager|\PHPUnit_Framework_MockObject_MockObject */
    private $entityManager;

    /** @var Payload|\PHPUnit_Framework_MockObject_MockObject */
    private $payload;

    public function setUp()
    {
        $this->payload = $this->getMockBuilder(Payload::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->form = $this->getMockBuilder(Patch::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->doctrineObject = $this->getMockBuilder(DoctrineObject::class)
            ->disableOriginalConstructor()
            ->setMethods(['hydrate'])
            ->getMock();
        $this->entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->patchClient = new PatchClient($this->payload, $this->form, $this->doctrineObject, $this->entityManager);
    }

    function testDataNotFound()
    {
        $input = [
            'id' => 'not found'
        ];
        $entRepo = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($entRepo));
        $entRepo->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null));

        $this->payload
            ->expects($this->once())
            ->method('withStatus')
            ->with($this->equalTo(PayloadInterface::STATUS_NOT_FOUND))
            ->will($this->returnSelf());
        $this->payload
            ->expects($this->once())
            ->method('withOutput')
            ->with($this->equalTo(['success' => false]));

        /** @var PayloadInterface $result */
        $result = $this->patchClient->__invoke($input);
    }

    function testBadRequest()
    {
        $input = [
            'id' => 'good-Id',
            'a' => '1',
            'b' => '2',
            'c' => '3',
        ];
        $mockClientEntity = $this->getMock(Client::class);
        $entRepo = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($entRepo));
        $entRepo->expects($this->once())
            ->method('find')
            ->will($this->returnValue($mockClientEntity));

        $this->form
            ->expects($this->once())
            ->method('getInputNames')
            ->will($this->returnValue([]));

        $this->payload
            ->expects($this->once())
            ->method('withStatus')
            ->with($this->equalTo(PayloadInterface::STATUS_NOT_MODIFIED))
            ->will($this->returnSelf());
        $this->payload
            ->expects($this->once())
            ->method('withOutput')
            ->with($this->equalTo(['success' => true]));

        /** @var PayloadInterface $result */
        $result = $this->patchClient->__invoke($input);
    }

    function testInvalidInput()
    {
        $input = [
            'id' => 'good-Id',
            'a' => '1',
            'b' => '2',
            'c' => '3',
        ];
        $mockClientEntity = $this->getMock(Client::class);
        $entRepo = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($entRepo));
        $entRepo->expects($this->once())
            ->method('find')
            ->will($this->returnValue($mockClientEntity));

        $this->form
            ->expects($this->once())
            ->method('getInputNames')
            ->will($this->returnValue(['a' => 1, 'b' => 2, 'c' => 3]));
        $this->form->expects($this->once())
            ->method('filter')
            ->will($this->returnValue(false));

        $this->payload
            ->expects($this->once())
            ->method('withStatus')
            ->with($this->equalTo(PayloadInterface::STATUS_UNPROCESSABLE_ENTITY))
            ->will($this->returnSelf());
        $this->payload
            ->expects($this->once())
            ->method('withOutput')
            ->with($this->equalTo(['success' => false, 'hints' => null]));

        /** @var PayloadInterface $result */
        $result = $this->patchClient->__invoke($input);
    }

    function testValidInput()
    {
        $input = [
            'id' => 'good-Id',
            'a' => '1',
            'b' => '2',
            'c' => '3',
        ];
        $mockClientEntity = $this->getMock(Client::class);
        $entRepo = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($entRepo));
        $entRepo->expects($this->once())
            ->method('find')
            ->will($this->returnValue($mockClientEntity));

        $this->form
            ->expects($this->once())
            ->method('getInputNames')
            ->will($this->returnValue(['a' => 1, 'b' => 2, 'c' => 3]));
        $this->form->expects($this->once())
            ->method('filter')
            ->will($this->returnValue(true));

        $this->form
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue([]));

        $this->payload
            ->expects($this->once())
            ->method('withStatus')
            ->with($this->equalTo(PayloadInterface::STATUS_ACCEPTED))
            ->will($this->returnSelf());
        $this->payload
            ->expects($this->once())
            ->method('withOutput')
            ->with($this->equalTo(['success' => true, 'id' => null]));

        /** @var PayloadInterface $result */
        $result = $this->patchClient->__invoke($input);
    }

    function testDoctrineThrowsException()
    {
        $input = [
            'id' => 'good-Id',
            'a' => '1',
            'b' => '2',
            'c' => '3',
        ];
        $entRepo = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($entRepo));
        $entRepo->expects($this->once())
            ->method('find')
            ->will($this->throwException(new \Exception));//should probably figure out what this would actually throw

        $this->payload
            ->expects($this->once())
            ->method('withStatus')
            ->with($this->equalTo(PayloadInterface::STATUS_SERVICE_UNAVAILABLE))
            ->will($this->returnSelf());
        $this->payload
            ->expects($this->once())
            ->method('withOutput')
            ->with($this->equalTo(['success' => false]));

        /** @var PayloadInterface $result */
        $result = $this->patchClient->__invoke($input);
    }
}