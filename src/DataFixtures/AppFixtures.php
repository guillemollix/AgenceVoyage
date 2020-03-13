<?php
namespace App\DataFixtures;

use App\Entity\Region;
use App\Entity\Room;
use App\Entity\Owner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    // définit un nom de référence pour une instance de Region
    public const IDF_REGION_REFERENCE = 'idf-region';
    public const BERRY_REGION_REFERENCE = 'berry-region';
    public const KANTO_REGION_REFERENCE = 'kanto-region';
    public const TRANSYLVANIA_REGION_REFERENCE = 'transylvania_region';
    public const NYC_REGION_REFERENCE = 'nyc-region';

    public function load(ObjectManager $manager)
    {
        //...

        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Ile de France");
        $region->setPresentation("La région française capitale");
        $manager->persist($region);

        $manager->flush();
        // Une fois l'instance de Region sauvée en base de données,
        // elle dispose d'un identifiant généré par Doctrine, et peut
        // donc être sauvegardée comme future référence.
        $this->addReference(self::IDF_REGION_REFERENCE, $region);

        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Berry");
        $region->setPresentation("Le coeur de la France d'antan");
        $manager->persist($region);
        $manager->flush();
        $this->addReference(self::BERRY_REGION_REFERENCE, $region);

        $region = new Region();
        $region->setCountry("JP");
        $region->setName("Kanto");
        $region->setPresentation("La région japonaise capitale");
        $manager->persist($region);
        $manager->flush();
        $this->addReference(self::KANTO_REGION_REFERENCE, $region);

        $region = new Region();
        $region->setCountry("RO");
        $region->setName("Transylvania");
        $region->setPresentation("Attention aux vampires !");
        $manager->persist($region);
        $manager->flush();
        $this->addReference(self::TRANSYLVANIA_REGION_REFERENCE, $region);

        $region = new Region();
        $region->setCountry("US");
        $region->setName("New-York City");
        $region->setPresentation("La ville la plus vibrante des états-unis d'amérique");
        $manager->persist($region);
        $manager->flush();
        $this->addReference(self::NYC_REGION_REFERENCE, $region);

        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Nord");
        $region->setPresentation("Dans le nord de la france");
        $manager->persist($region);
        $manager->flush();


        // ...

        $room = new Room();
        $room->setSummary("Beau poulailler ancien à Évry");
        $room->setDescription("très joli espace sur paille");
        $room->setAddress("13 Rue Paul Claudel");
        $room->setCapacity(3);
        $room->setPrice(20);
        $room->setSuperficy(30);
        // On peut plutôt faire une référence explicite à la référence
        // enregistrée précédamment, ce qui permet d'éviter de se
        // tromper d'instance de Region :
        $room->addRegion($this->getReference(self::IDF_REGION_REFERENCE));

        $owner = new Owner();
        $owner->setFirstname("John");
        $owner->setFamilyname("Doe");
        $room->setOwner($owner);

        $manager->persist($room);
        $manager->flush();

        $room = new Room();
        $room->setSummary("Chez Toto");
        $room->setDescription("Chambre libre chez toto");
        $room->setAddress("chez toto (au fond à droite)");
        $room->setCapacity(1);
        $room->setPrice(2.5);
        $room->setSuperficy(10);
        $room->addRegion($this->getReference(self::IDF_REGION_REFERENCE));

        $owner = new Owner();
        $owner->setFirstname("Toto");
        $owner->setFamilyname("Delafeuille");
        $room->setOwner($owner);

        $manager->persist($room);
        $manager->flush();

        $room = new Room();
        $room->setSummary("Foyer de TSP");
        $room->setDescription("Le foyer de TSP, acceuillant mais pas très confortable");
        $room->setAddress("rue Charles Fourier");
        $room->setCapacity(42);
        $room->setPrice(0);
        $room->addRegion($this->getReference(self::IDF_REGION_REFERENCE));

        $owner = new Owner();
        $owner->setFirstname("Telecom");
        $owner->setFamilyname("SudParis");
        $room->setOwner($owner);

        $manager->persist($room);
        $manager->flush();

        $room = new Room();
        $room->setSummary("Eglise rénovée");
        $room->setDescription("Une église rennaissance rénovée et transformée en chambre");
        $room->setAddress("Le plus loin possible de la civilisation");
        $room->setCapacity(2);
        $room->setPrice(35);
        $room->setSuperficy(40);
        $room->addRegion($this->getReference(self::BERRY_REGION_REFERENCE));

        $owner = new Owner();
        $owner->setFirstname("Jean-Pierre");
        $owner->setFamilyname("Lepaumé");
        $room->setOwner($owner);

        $manager->persist($room);
        $manager->flush();

        $room = new Room();
        $room->setSummary("Ferme");
        $room->setDescription("Faites gaffe aux chêvres");
        $room->setAddress("Banlieue de Bourges");
        $room->setCapacity(10);
        $room->setPrice(2);
        $room->setSuperficy(120);
        $room->addRegion($this->getReference(self::BERRY_REGION_REFERENCE));

        $owner = new Owner();
        $owner->setFirstname("Michel");
        $owner->setFamilyname("Tout court");
        $room->setOwner($owner);

        $manager->persist($room);
        $manager->flush();

        $room = new Room();
        $room->setSummary("Chez Yamada");
        $room->setDescription("Un adorable petit nid douillet au centre de la préfecture de Chiba");
        $room->setAddress("Quelqu'un viendra vous chercher à l'aéroport");
        $room->setCapacity(2);
        $room->setPrice(55);
        $room->setSuperficy(23);
        $room->addRegion($this->getReference(self::KANTO_REGION_REFERENCE));

        $owner = new Owner();
        $owner->setFirstname("Yamada");
        $owner->setFamilyname("Tôdo");
        $room->setOwner($owner);

        $manager->persist($room);
        $manager->flush();

        $room = new Room();
        $room->setSummary("Clan Matoi");
        $room->setDescription("Chambre dans un véritable village ninja");
        $room->setAddress("secret ancestral");
        $room->setCapacity(400);
        $room->setPrice(300);
        $room->setSuperficy(1000);
        $room->addRegion($this->getReference(self::KANTO_REGION_REFERENCE));

        $owner = new Owner();
        $owner->setFirstname("Kuromitsu");
        $owner->setFamilyname("Matoi");
        $room->setOwner($owner);

        $manager->persist($room);
        $manager->flush();

        $room = new Room();
        $room->setSummary("Penthouse");
        $room->setDescription("Appart' de délire en plein Manhattan");
        $room->setAddress("45st");
        $room->setCapacity(15);
        $room->setPrice(1050);
        $room->setSuperficy(200);
        $room->addRegion($this->getReference(self::NYC_REGION_REFERENCE));

        $owner = new Owner();
        $owner->setFirstname("Brad");
        $owner->setFamilyname("Smith");
        $room->setOwner($owner);

        $manager->persist($room);
        $manager->flush();

        $room = new Room();
        $room->setSummary("Studio de jazz réhabilité");
        $room->setDescription("Un lieu qui a vu jouer les plus mythiques jazmen");
        $room->setAddress("The Bronx");
        $room->setCapacity(4);
        $room->setPrice(45.5);
        $room->setSuperficy(60);
        $room->addRegion($this->getReference(self::NYC_REGION_REFERENCE));

        $owner = new Owner();
        $owner->setFirstname("James");
        $owner->setFamilyname("Bentham");
        $room->setOwner($owner);

        $manager->persist($room);
        $manager->flush();

        $room = new Room();
        $room->setSummary("Château pas hanté");
        $room->setDescription("Château n'ayant jamais abrité aucun esprit");
        $room->setAddress("Route du village");
        $room->setCapacity(50);
        $room->setPrice(120);
        $room->setSuperficy(600);
        $room->addRegion($this->getReference(self::TRANSYLVANIA_REGION_REFERENCE));

        $owner = new Owner();
        $owner->setFirstname("Vlad III");
        $owner->setFamilyname("Tepes");
        $room->setOwner($owner);

        $manager->persist($room);
        $manager->flush();
    }

    //...
}