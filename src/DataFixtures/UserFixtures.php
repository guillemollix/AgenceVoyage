<?php

namespace App\DataFixtures;

use App\Entity\Owner;
use App\Entity\Region;
use App\Entity\Room;
use App\Repository\RegionRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $this->LoadUsers($manager);
        
        $manager->flush();
    }
    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$email,$plainPassword,$role,$summary,$description,$adress,$capacity,$price,$superficy,$region,$image,$fn,$ln]) {
            $user = new User();
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setEmail($email);
            $user->setPassword($encodedPassword);
            $user->setRoles($role);
            $manager->persist($user);
            $owner = new Owner();
            $owner->setUser($user);
            $owner = new Owner();
            $owner->setUser($user);
            $owner->setFirstname($fn);
            $owner->setFamilyname($ln);
            $manager->persist($owner);

            $room = new Room();
            $room->setOwner($owner);
            $room->setSummary($summary);
            $room->setDescription($description);
            $room->setAddress($adress);
            $room->setCapacity($capacity);
            $room->setPrice($price);
            $room->setSuperficy($superficy);
            $room->addRegion(($manager->getRepository(Region::class)->find($region)));
            $room->setImageFile(new File(__DIR__.'/images/'.$image));
            $room->setImageName($image);
            $manager->persist($room);
        }
    }
    
    private function getUserData()
    {
        yield ['chris@localhost','chris',['ROLE_USER'],"Le Palace","Très grand château","Quelque part dans le Nord",80,800,4000,6,'5de0022818dfa029586923.jpg',"Chris","Christodoulou"];
        yield ['anna@localhost','anna',['ROLE_ADMIN'],"Le cachot","Joli petit cachot. Idéal pour passer l'hiver","Aux Épinettes",7,4,147,1,'5ddffe5647531988035073.jpg',"Anna","Anna"];
        
    }
}
