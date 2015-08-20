<?php
namespace Sonata\NotificationBundle\Iterator;

use Pheanstalk\Pheanstalk;
use Leezy\PheanstalkBundle\Proxy\PheanstalkProxy as PheanstalkProxy;
use Pheanstalk\Job as Job;

class BeanstalkdIterator implements MessageIteratorInterface
{
    protected $tube;

    protected $message;

    protected $job;

    protected $connection;

    protected $counter;

    /**
     * @param \PhpAmqpLib\tube\AMQPtube $tube
     * @param $queue
     */
    public function __construct(PheanstalkProxy $connection, $tube)
    {
        $this->tube = $tube;
        $this->connection = $connection;
        $this->connection->watch($this->tube);
        $this->counter = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        $this->wait();
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        $this->counter;
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return count($this->connection->listTubesWatched(true));
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {

        $this->wait();

        return $this->message;
    }

    protected function wait()
    {
        while ($this->valid()) {
            $this->receiveMessage($this->connection->reserve());

            break;
        }
    }

    /**
     * @param \PhpAmqpLib\Message\AMQPMessage $AMQMessage
     */
    public function receiveMessage($job)
    {
        $this->job = $job;

        $data = json_decode($this->job->getData(), true);

        $message = new \Sonata\NotificationBundle\Model\Message();
        $data['body']['Job'] = $job;
        $message->setBody($data['body']);
        $message->setType($data['type']);
        $message->setState($data['state']);

        ++$this->counter;

        $this->message = $message;
    }
}