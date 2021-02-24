<?php


namespace App\Service;


use App\Entity\Campus;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FileSerializer
{
    private $dataDirectory;
    private UserRepository $userRepo;
    private EntityManagerInterface $em;


    public function __construct(string $dataDirectory, EntityManagerInterface $em, UserRepository $userRepo)
    {
        $this->dataDirectory = $dataDirectory;
        $this->em = $em;
        $this->userRepo = $userRepo;
    }

    private function getDataFromFile(): array
    {
        $file = $this->dataDirectory . 'user.csv';

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        $normalizers = [new ObjectNormalizer()];

        $encoders = [
            new CsvEncoder(),
            new XmlEncoder(),
            new YamlEncoder(),
            new JsonEncoder(),
        ];

        $serializer = new Serializer($normalizers, $encoders);

        /**@var string $fileString */
        $fileString = file_get_contents($file);

        $data = $serializer->decode($fileString, $fileExtension);

        if (array_key_exists('result', $data)) {
            return $data['result'];
        }
        return $data;
    }

    public function createUsers(): string
    {
      $usersCreated = 0;

        foreach ($this->getDataFromFile() as $row) {
            if (array_key_exists('email', $row) && !empty($row['email'])) {
                $user = $this->userRepo->findOneBy(['email' => $row['email']]);

                if (!$user) {

                    $campus = new Campus();
                    $campus->setId($row['campus_id']);

                    $user = new User();
//                    $user.csv->setCampus($campus);
                    $user->setName($row['name']);
                    $user->setSurName($row['surname']);
                    $user->setUsername($row['username']);
                    $user->setEmail($row['email']);
                    $user->setTelephone($row['telephone']);

                    // Afin de retrouver juste la chaine de caracteres
                    $role = substr($row['roles'],2,-2);

                    $user->setRoles([$role]);
                    $user->setEmail($row['email']);
                    $user->setPassword($row['password']);
                    $user->setActif($row['actif']);
                    $user->setUrlPhoto($row['url_photo']);

                    $this->em->persist($user);

                    $usersCreated++;
                }
            }
        }
        $this->em->flush();

        if ($usersCreated > 1) {
            $message = "{$usersCreated} UTILISATEURS CREES EN BASE DE DONNEES.";
        } elseif ($usersCreated === 1) {
            $message = "1 UTILISATEUR CREE EN BASE DE DONNEES.";
        } else {
            $message = "AUCUN UTILISATEUR CREE EN BASE DE DONNEES.";
        }
       return $message;
    }
}