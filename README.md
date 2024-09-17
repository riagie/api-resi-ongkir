## Shipping Cost and Package Tracking API
 The Shipping Cost and Package Tracking API provides an easy and efficient way to check shipping costs and track the status of packages in real-time. This API is designed to simplify the process of obtaining shipping rates and tracking information across various couriers.
#### Information results
```
{
    "RC": "0200",
    "RCM": "SUCCESS",
    "DATA": {
        "WAYBILL_NUMBER": "XXXXX",
        "EXPEDITION": {
            "NAME": "SICEPAT",
            "SERVICE": "HALU",
            "PRICE": 10200,
            "ESTIMATE_DELIVERY_DAYS": "",
            "ORIGIN": "CGK10302",
            "DESTINATION": "BDO10055"
        },
        "WEIGHT": 1,
        "DESCRIPTION": "",
        "SEND_DATE": "2024-08-18 18:30:00",
        "SENDER": {
            "NAME": "Ilo Official Shop",
            "ADDRESS": "Jakarta Pusat"
        },
        "RECEIVER": {
            "NAME": "XXXXX",
            "ADDRESS": "Kiaracondong, KOTA BANDUNG",
            "DESCRIPTION": "XXXXX",
            "DATE_TIME": "2024-08-19 15:05:00",
            "IMG": "https://imgproxy.sicepat.com/g7yW4cLTKqKhKi9iECiUQFRrzgUMZF3MntPqNjhbwpY/rs:fit:768:1024:0/g:no/aHR0cHM6Ly9zaWNlcGF0bWFzdGVyZGF0YS5zMy5hbWF6b25hd3MuY29tL2F0dGFjaG1lbnRzL3Bob3RvUE9ELzAwNDMzOTkxMjM1NA.jpg"
        },
        "COURIER": {
            "DELIVERY": "",
            "PICKUP": ""
        },
        "STATUS": "DELIVERED",
        "DATE_TIME": "2024-08-19 15:05:00",
        "TRACK_HISTORY": [
            {
                "DATE_TIME": "2024-08-18 09:25:00",
                "STATUS": "PICKREQ",
                "DESCRIPTION": "Terima permintaan pick up dari [Shopee]"
            },
            {
                "DATE_TIME": "2024-08-18 14:29:00",
                "STATUS": "PICK",
                "DESCRIPTION": "Paket telah di pick up oleh [SIGESIT - FAJAR ALAMSYAH]"
            },
            {
                "DATE_TIME": "2024-08-18 18:30:00",
                "STATUS": "IN",
                "DESCRIPTION": "Paket telah di input (manifested) di Jakarta Pusat [Jakpus Tanah Abang FM]"
            },
            {
                "DATE_TIME": "2024-08-18 22:07:00",
                "STATUS": "OUT",
                "DESCRIPTION": "Paket keluar dari Jakarta Pusat [Jakpus Tanah Abang FM]"
            },
            {
                "DATE_TIME": "2024-08-19 04:17:00",
                "STATUS": "IN",
                "DESCRIPTION": "Paket telah diterima di Jakarta Timur [Middle Mile Jakarta]"
            },
            {
                "DATE_TIME": "2024-08-19 05:37:00",
                "STATUS": "OUT",
                "DESCRIPTION": "Paket keluar dari Jakarta Timur [Middle Mile Jakarta]"
            },
            {
                "DATE_TIME": "2024-08-19 09:09:00",
                "STATUS": "IN",
                "DESCRIPTION": "Paket telah di terima di Bandung [Middle Mile Bandung]"
            },
            {
                "DATE_TIME": "2024-08-19 09:45:00",
                "STATUS": "OUT",
                "DESCRIPTION": "Paket keluar dari Bandung [Middle Mile Bandung]"
            },
            {
                "DATE_TIME": "2024-08-19 10:33:00",
                "STATUS": "IN",
                "DESCRIPTION": "Paket telah di terima di Bandung [Bandung Cibeunying]"
            },
            {
                "DATE_TIME": "2024-08-19 10:57:00",
                "STATUS": "ANT",
                "DESCRIPTION": "Paket dibawa [SIGESIT - YUSUP HENDRO PERMANA]"
            },
            {
                "DATE_TIME": "2024-08-19 15:05:00",
                "STATUS": "DELIVERED",
                "DESCRIPTION": ""
            }
        ]
    }
}
```

#### Endpoints
Check Endpoints
- `{url}`
- response 
```
{
    "/cek-resi/{expedition}/{waybill_number}": {
        "method": "GET",
        "parameters": {
            "expedition": "JNE"
            "waybill_number": "004339912354"
        }
    },
    "/service": {
        "method": "GET"
    }
}
```