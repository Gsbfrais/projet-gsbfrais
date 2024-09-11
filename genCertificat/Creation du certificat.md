Dans un terminal ouvert en **mode administrateur** dans ce dossier `/genCertificat`, exécuter la commande suivante :

```
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout cert-key.pem -out cert.pem -config req.conf -sha256
```

puis 

```
certutil -addstore -f "ROOT" ./cert.pem
```



