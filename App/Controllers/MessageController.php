<?php

namespace Chat;

class MessageController extends \Controller
{
    public function before_filter()
    {
        \User::restrict();
    }

    public function index()
    {
        $em = \Model::getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('m')->from('\Chat\Message', 'm')->where('m.owner = :user')
            ->setParameter('user', \User::current_user());

        $results = $qb->getQuery()->getResult();
        $response = array();
        foreach ($results as $result)
        {
            $response[] = $result->toArray();
        }
        $this->return_json($response);
    }

    private function createOrUpdate($data)
    {
        if (isset($data["id"]))
            $message = Message::find($data["id"]);
        else
            $message = null;

        if (!is_object($message))
        {
            $message = new Message();
        }

        $message->setOwner(\User::current_user());
        $message->setContent($data["content"]);

        if (isset($data["participants"]) && is_array($data["participants"]))
        {
            foreach ($data["participants"] as $participant)
            {
                $message->addParticipant($participant);
            }
            unset($data["participants"]);
        }

        unset($data["content"]);
        unset($data["owner"]);
        unset($data["id"]);

        $message->save();

        return $message->toArray();
    }

    public function create()
    {
        $data = $this->getRequestData();
        $this->return_json($this->createOrUpdate($data));
    }

    public function update($data = array())
    {
        $id = $data["id"];
        $data = $this->getRequestData();

        if (isset($data["id"]))
            $message = Message::find($data["id"]);
        else
            $message = null;

        if (is_object($message))
        {
            if ($message->getOwner() == \User::current_user())
            {
                $this->return_json($this->createOrUpdate($data));
            }
            else
            {
                $this->json_error("This message does not exist.", 404);
            }
        }
        else
        {
            $this->json_error("This message does not exist.", 404);
        }
    }


    public function destroy($data = array())
    {
        $message = Message::find($data["id"]);
        if (is_object($message))
        {
            if ($message->getOwner() == \User::current_user())
            {
                $message->delete();
                $this->json_message("Successfully deleted message.");
            }
            else
            {
                $this->json_error("This message does not exist.", 404);
            }
        }
        else
        {
            $this->json_error("This message does not exist.", 404);
        }
    }
}