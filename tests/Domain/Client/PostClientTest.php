<?php
namespace RaffleToolsTests\Domain\Client;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Equip\Adr\PayloadInterface;
use Equip\Payload;
use RaffleTools\Domain\Client\Form\Post;
use RaffleTools\Domain\Client\PostClient;

class PostClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var PostClient */
    private $postClient;

    /** @var Post|\PHPUnit_Framework_MockObject_MockObject */
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
        $this->form = $this->getMockBuilder(Post::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->doctrineObject = $this->getMockBuilder(DoctrineObject::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->postClient = new PostClient($this->payload, $this->form, $this->doctrineObject, $this->entityManager);
    }

    function testInvalidInput()
    {
        $input = [
            'bad' => 'data'
        ];
        $this->form
            ->expects($this->once())
            ->method('filter')
            ->will($this->returnValue(false));
        $this->form
            ->expects($this->once())
            ->method('getMessages');

        $this->payload
            ->expects($this->once())
            ->method('withStatus')
            ->with($this->equalTo(PayloadInterface::STATUS_UNPROCESSABLE_ENTITY))
            ->will($this->returnSelf());
        $this->payload
            ->expects($this->once())
            ->method('withOutput')
            ->with($this->equalTo(['success' => false, 'hints' => null])); //hints is null because the mock class returns null

        /** @var PayloadInterface $result */
        $result = $this->postClient->__invoke($input);
    }

    function testValidInput()
    {
        $input = [
            'good' => 'data'
        ];
        $this->form
            ->expects($this->once())
            ->method('filter')
            ->will($this->returnValue(true));

        $this->form
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue([]));

        $this->doctrineObject
            ->expects($this->once())
            ->method('hydrate');
        $this->entityManager
            ->expects($this->once())
            ->method('persist');
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->payload
            ->expects($this->once())
            ->method('withStatus')
            ->with($this->equalTo(PayloadInterface::STATUS_CREATED))
            ->will($this->returnSelf());
        $this->payload
            ->expects($this->once())
            ->method('withOutput')
            ->with($this->equalTo(['success' => true, 'id' => null]));

        /** @var PayloadInterface $result */
        $result = $this->postClient->__invoke($input);
    }

    function testValidInputAndDoctrineThrowsError()
    {
        $input = [
            'good' => 'data'
        ];
        $this->form
            ->expects($this->once())
            ->method('filter')
            ->will($this->returnValue(true));

        $this->form
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue([]));

        $this->doctrineObject
            ->expects($this->once())
            ->method('hydrate');
        $this->entityManager
            ->expects($this->once())
            ->method('persist');
        $this->entityManager
            ->expects($this->once())
            ->method('flush')
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
        $result = $this->postClient->__invoke($input);
    }
}