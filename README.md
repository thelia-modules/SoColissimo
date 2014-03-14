SoColissimo Module v1.0
author: <info@thelia.net>

Summary
=======

### fr_FR
1. Installation
2. Utilisation
3. Boucles
4. Intégration

### en_US
1. Install notes
2. How to use
3. Loops
4. Integration


fr_FR
=====

Installation
------------
Pour installer le module SoColissimo, téléchargez l'archive et extrayez la dans le dossier dossierDeThelia/local/modules

Utilisation
-----------
Tout d'abord, allez dans votre back-office, onglet Modules, et activez le module SoColissimo.
Allez ensuite sur la page de configuration de ce dernier, onglet "Configurer SoColissimo", et entrez vos identifiants
pour le service SoColissimo. 
Pour importer les fichiers généré via l'export avec Expeditor INET, vous avez besoin de THELIA_INET.FMT présent dans l'archive du module.

Boucles
-------
1. socolissimo.check.rights
    - Arguments:
        Aucun
    - Sorties:
        1. $ERRMES:  message d'erreur
        2. $ERRFILE: fichier où le problème a été detecté
    - Utilisation:
        ```{loop name="yourloopname" type="socolissimo.check.rights"}<!-- your template -->{/loop}```

2. socolissimo
    - Arguments:
        1. area | obligatoire | id de l'area dont on veut savoir les prix
    - Sorties:
        1. $MAX_WEIGHT: poids maximal pour le prix
        2. $PRICE: prix
    - Utilisation:
        ```{loop name="yourloopname" type="socolissimo"}<!-- your template -->{/loop}```

3. socolissimoid
    - Arguments:
        Aucun
    - Sorties:
        1. $MODULE_ID: id du module SoColissimo
    - Utilisation:
        ```{loop name="yourloopname" type="socolissimoid"}<!-- your template -->{/loop}```

4. socolissimo.around
    - Arguments:
        1. zipcode | optionnel | code postal de la ville recherchée
        2. city    | optionnel | nom de la ville recherchée
    - Sorties:
        1. $LONGITUDE: longitude du point relais
        2. $LATITUDE : latitude du point relais
        3. $CODE     : code spécifique SoColissimo du point relais
        4. $ADDRESS  : adresse du point relais
        5. $ZIPCODE  : code postal du point relais
        6. $CITY     : ville du point relais
        7. $DISTANCE : distance entre le point relais et l'adresse du client/l'adresse recherchée
    - Utilisation:
        ```{loop name="yourloopname" type="socolissimo.around"}<!-- your template -->{/loop}```

5. address.socolissimo
    - Arguments:
        Les mêmes que la boucle address
    - Sorties:
        Les mêmes que la boucle address, mais avec l'adresse du point relais.
    - Utilisation:
        ```{loop name="yourloopname" type="address.socolissimo"}<!-- your template -->{/loop}```

6. order.notsent.socolissimo
    - Arguments:
        Aucun
    - Sorties:
        Les même sorties que la boucle order, mais avec uniquement les commandes SoColissimo non envoyées.
    - Utilisation:
        ```{loop name="yourloopname" type="order.notsent.socolissimo"}<!-- your template -->{/loop}```

Intégration
-----------
Un exemple d'intégration avec une google map vous est proposé avec le thème par default de Thelia.
Pour l'installer, veuillez copier les fichiers contenus dans dossierDeSoColissimo/templates/frontOffice/default et
dossierDeSoColissimo/templates/frontOffice/default/ajax respectivement dans le dossier
dossierDeThelia/templates/frontOffice/default et dossierDeSoColissimo/templates/frontOffice/default/ajax

en_US
=====
Install notes
-----------
To install SoColissimo module, download the archive and extract it in pathToThelia/local/modules

How to use
-----------
First, go to your back office, tab Modules, and activate the module SoColissimo.
Then go to Socolissimo configure page, tab "Configure SoColissimo" and enter your Socolissimo id and password.
To import exported files in Expeditor INET, you need the file THELIA_INET.FMT, that is in the archive.

Loops
-----
1. socolissimo.check.rights
    - Arguments:
        None
    - Output:
        1. $ERRMES:  error message
        2. $ERRFILE: file where the error has been detected
    - Usage:
        ```{loop name="yourloopname" type="socolissimo.check.rights"}<!-- your template -->{/loop}```

2. socolissimo
    - Arguments:
        1. area | mandatory | id de l'area dont on veut savoir les prix
    - Output:
        1. $MAX_WEIGHT: max weight for the price
        2. $PRICE: price
    - Usage:
        ```{loop name="yourloopname" type="socolissimo"}<!-- your template -->{/loop}```

3. socolissimoid
    - Arguments:
        None
    - Output:
        1. $MODULE_ID: id of the module SoColissimo
    - Usage:
        ```{loop name="yourloopname" type="socolissimoid"}<!-- your template -->{/loop}```

4. socolissimo.around
    - Arguments:
        1. zipcode | optionnel | zipcode of the searched city
        2. city    | optionnel | name of the searched city
    - Output:
        1. $LONGITUDE: longitude of the pickup & go store
        2. $LATITUDE : latitude of the pickup & go store
        3. $CODE     : ID of the pickup & go store
        4. $ADDRESS  : address of the pickup & go store
        5. $ZIPCODE  : zipcode of the pickup & go store
        6. $CITY     : city of the pickup & go store
        7. $DISTANCE : distance between the store and the customer's address/searched address
    - Usage:
        ```{loop name="yourloopname" type="socolissimo.around"}<!-- your template -->{/loop}```

5. address.socolissimo
    - Arguments:
        The same as the loop address
    - Output:
        The same as the loop address, but with pickup & go store's address
    - Usage:
        ```{loop name="yourloopname" type="address.socolissimo"}<!-- your template -->{/loop}```

6. order.notsent.socolissimo
    - Arguments:
        None
    - Output:
        The same as the loop order, but with not sent Socolissimo orders.
    - Usage:
        ```{loop name="yourloopname" type="order.notsent.socolissimo"}<!-- your template -->{/loop}```


Integration
-----------
A integration example is available for the default theme of Thelia.
To install it, copy the files of pathToSoColissimo/templates/frontOffice/default and
pathToSoColissimo/templates/frontOffice/default/ajax respectively in pathToThelia/templates/frontOffice/default
and pathToThelia/templates/frontOffice/default/ajax
