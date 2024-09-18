<?php

namespace App\Helpers;

class Expedition
{
    private static $expeditions = [
        [
            "EXPEDITION" => "ALFAMART",
            "DESCRIPTION" => "Alfamart provides logistics services in partnership with various couriers, focusing on convenience through their retail network.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "ANTERAJA",
            "DESCRIPTION" => "AnterAja provides comprehensive delivery services across Indonesia, focusing on quick and efficient logistics.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "CAHAYA",
            "DESCRIPTION" => "Cahaya offers logistics and delivery services with a focus on comprehensive coverage.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "DAKOTA",
            "DESCRIPTION" => "Dakota Cargo specializes in freight and cargo delivery services, providing solutions for various logistics needs.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "DELIVEREE",
            "DESCRIPTION" => "Deliveree provides on-demand delivery and logistics services with a focus on convenience and customer satisfaction.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "DHL",
            "DESCRIPTION" => "DHL provides international and domestic courier services with a focus on global reach and reliable delivery.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "ESL",
            "DESCRIPTION" => "ESL provides parcel and logistics services with a focus on efficiency and reliability.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "EXPEDITO",
            "DESCRIPTION" => "Expedito offers fast and reliable parcel delivery services with a focus on efficiency.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "FIRSTLOGISTICS",
            "DESCRIPTION" => "First Logistics offers a range of logistics solutions including parcel and cargo delivery services.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "GOJEK",
            "DESCRIPTION" => "GoSend, provided by Gojek, offers fast and convenient delivery services through their extensive network.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "GRAB",
            "DESCRIPTION" => "GrabExpress, part of Grab, offers on-demand delivery services with a focus on convenience and speed.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "IDEXPRESS",
            "DESCRIPTION" => "ID Express offers a range of logistics solutions including domestic and international delivery services.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "IDLCARGO",
            "DESCRIPTION" => "IDL Cargo offers a range of cargo and delivery services with an emphasis on efficiency.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "INDAH",
            "DESCRIPTION" => "Indah Cargo offers cargo and logistics services with a focus on timely deliveries and comprehensive coverage.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "INDOMARET",
            "DESCRIPTION" => "Indomaret offers parcel delivery services through partnerships with courier companies, leveraging their retail presence.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "JNE",
            "DESCRIPTION" => "JNE (Jalur Nugraha Ekakurir) is one of the largest logistics and courier companies in Indonesia, known for its extensive network and reliable delivery services.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "JNT",
            "DESCRIPTION" => "J&T Express provides fast and reliable delivery services throughout Indonesia and other countries.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "JNTCARGO",
            "DESCRIPTION" => "J&T Cargo focuses on freight and cargo delivery solutions, catering to both domestic and international needs.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "JTL",
            "DESCRIPTION" => "JTL Express offers various logistics solutions including parcel delivery and freight services.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "LALAMOVE",
            "DESCRIPTION" => "Lalamove provides on-demand delivery services with a focus on efficiency and flexible solutions.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "LION",
            "DESCRIPTION" => "Lion Parcel, part of the Lion Group, offers reliable parcel delivery services across Indonesia.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "NCS",
            "DESCRIPTION" => "Nusantara Card Semesta (NCS) provides delivery and logistics services with a focus on efficiency and coverage.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "NINJA",
            "DESCRIPTION" => "Ninja Xpress specializes in fast and reliable delivery services across Indonesia.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "PAHALA",
            "DESCRIPTION" => "Pahala Express provides comprehensive delivery and logistics services with a focus on reliability.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "PANDU",
            "DESCRIPTION" => "Pandu Logistics offers a range of logistics and delivery services with a focus on reliability.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "PAXEL",
            "DESCRIPTION" => "Paxel offers innovative and fast delivery services with a focus on technology-driven logistics solutions.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "POS",
            "DESCRIPTION" => "Pos Indonesia offers traditional postal services as well as logistics and courier solutions across the country.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "REX",
            "DESCRIPTION" => "Rex Express specializes in parcel and cargo delivery services, focusing on timely and reliable service.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "ROSALIA",
            "DESCRIPTION" => "Rosalia Express provides cargo and logistics services with a reputation for reliability and efficiency.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "RPX",
            "DESCRIPTION" => "RPX Holding provides comprehensive logistics solutions including parcel and freight delivery services.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "SENTRAL",
            "DESCRIPTION" => "Sentral Cargo offers comprehensive cargo and logistics services with a focus on reliability.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "SAP",
            "DESCRIPTION" => "SAP Express offers a range of delivery and logistics services with an emphasis on speed and reliability.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "SICEPAT",
            "DESCRIPTION" => "SiCepat Express provides efficient and fast courier services with a focus on customer satisfaction.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "SLIS",
            "DESCRIPTION" => "Solusi Ekspres (SLIS) provides efficient and reliable delivery services with a focus on customer needs.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "STAR",
            "DESCRIPTION" => "Star Cargo provides comprehensive delivery and logistics services with a focus on reliability.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "TIKI",
            "DESCRIPTION" => "TIKI (Titipan Kilat) is a well-known courier service provider in Indonesia, offering both express and regular delivery options.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "21EXPRESS",
            "DESCRIPTION" => "21 Express specializes in parcel delivery services with an emphasis on quick and reliable delivery.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "DELIVEREE",
            "DESCRIPTION" => "Deliveree provides on-demand delivery and logistics services with a focus on convenience and customer satisfaction.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "RAYSPEED",
            "DESCRIPTION" => "Rayspeed provides logistics and delivery services with a focus on speed and reliability.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "FIRSTLOGISTICS",
            "DESCRIPTION" => "First Logistics offers a range of logistics solutions including parcel and cargo delivery services.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "IDLCARGO",
            "DESCRIPTION" => "IDL Cargo offers a range of cargo and delivery services with an emphasis on efficiency.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "PCP",
            "DESCRIPTION" => "PCP offers a range of logistics solutions including parcel delivery and freight services.",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "JET",
            "DESCRIPTION" => "JET offers express delivery services with a focus on speed and customer satisfaction.",
            "CODE" => []
        ],
    ];    

    public static function all()
    {
        return self::$expeditions;
    }

    public static function find(string $code): ?array
    {
        foreach (self::$expeditions as $expedition) {
            if (in_array($code, $expedition['CODE'])) {
                return $expedition;
            }
        }

        return null;
    }
}
