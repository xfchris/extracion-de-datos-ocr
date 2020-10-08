This application extract information using optical character recognition OCR. This app makes a request at the google.vision app.
The input files maybe can be pdf or images files with a restriction 6 pages.

Como funciona?:
1. Clonar el repositorio
2. ejecutar en la carpeta del repositorio clonado el comando: php7.3 -S localhost:8887 para arrancar un servidor de desarrollo en PHP
3. Una vez iniciado el server, entrar a la url: http://localhost:8887/test.php?escritura=1&op=textoOcr
esto mostrará el texto como lo extrae el OCR de las escrituras escaneadas en PDF.
4. entrar a la url http://localhost:8887/test.php?escritura=1  esto mostrará la informacion extraida que se necesita en el proyecto. esta informacion son:
4.1 Actos
4.2 Compradores
4.3 Vendedores
4.4 Ubicación del inmueble
5. retorna una estructura en json de la informacion organizada de esta forma: https://i.imgur.com/73u4AMe.png
6. existen otros campos como _ocrFormato, tipoDocumento y tipoNegocio que generan con base a los 4 datos principales.
