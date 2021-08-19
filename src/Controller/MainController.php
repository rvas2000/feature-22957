<?php

namespace App\Controller;

use App\DTO\DtoLicensePlatesRequestData;
use App\Entity\LicensePlate;
use App\Exception\ApiException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    protected EntityManagerInterface $em;
    protected LoggerInterface        $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em     = $em;
        $this->logger = $logger;
    }

    /**
     * @Route ("/push", name="push", methods={"POST"})
     */
    public function push(Request $request): Response
    {
        try {
            $status  = Response::HTTP_OK;
            $message = 'OK';

            $requestData = new DtoLicensePlatesRequestData($request->getContent());

            foreach ($requestData->data as $item) {
                $licensePlate = new LicensePlate();
                $licensePlate
                    ->setCreatedAt(new \DateTime())
                    ->setLicensePlate($item->licensePlate)
                    ->setDateFrom($item->from)
                    ->setDateTo($item->to);
                $this->em->persist($licensePlate);
            }

            $this->em->flush();

        } catch (\JsonException $e) {
            $status  = Response::HTTP_BAD_REQUEST;
            $message = 'json error';
            $this->logger->error($e->getMessage());
        } catch (ApiException $e) {
            $status  = Response::HTTP_BAD_REQUEST;
            $message = $e->getMessage();
            $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            $status  = Response::HTTP_BAD_REQUEST;
            $message = 'unrecognized error';
            $this->logger->error($e->getMessage());
        }

        return new Response($message, $status, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

}
