# Mi portafolio
Hola, mi nombre es Óscar González, desarrollo sitios web y sistemas ETL. (Extracción y transformación de datos en información para su cargado en bases de datos consolidada).
Diseño interfaces gráficas para la web y escritorio. Desarrollo para empresas, para intereses particulares y software libre.  Estudie Tecnología en Análisis y Desarrollo de Sistemas de Información. ADSI-SENA. Tengo experiencia como desarrollador desde el 2020. Me intereso en proyectos de programación de alto y bajo nivel. Me gusta la electrónica y el internet de las cosas.

## Proyectos
Los siguiente proyectos son una pequeña muestra de mi conocimiento y mi forma de trabajar:  

## python-software-architecture
Esta es una propuesta de arquitectura de software en python, donde se facilita la actualización automática de dependencias que no se encuentren en gestores de paquetes de python, sino en repositorios remotos. Para esta muestra, está configurado para usar los siguientes desarrollos:
| Desarrollo |¿Qué es o qué hace en este proyecto?|Enlace|
|:---|:---|:---|
|<b>cliente-webservice-sipsa Servicio</b>| Facilita el acceso a la base de datos de la Webservice de SIPSA del DANE (Servicio SOAP - WSDL).| [Saber mas...](https://github.com/oigonzalezp2024/oigonzalezp2024.github.io/blob/main/README.md#cliente-webservice-sipsa) |
|<b>delimitedData</b>|Delimita la data a ser analizada. ([*](https://github.com/oigonzalezp2024/oigonzalezp2024.github.io/blob/main/README.md#sobre-la-webservice-sipsa-del-dane))|[Saber mas...](https://github.com/oigonzalezp2024/oigonzalezp2024.github.io/blob/main/README.md#delimiteddata)|
|<b>transformData</b>|Transforma los datos de acuerdo al modelo de negocio.| [Saber mas...](https://github.com/oigonzalezp2024/oigonzalezp2024.github.io/blob/main/README.md#transformdata) |
|<b>matployDraw</b>|Transforma los datos en gráficos estadísticos.| [Saber mas...](https://github.com/oigonzalezp2024/oigonzalezp2024.github.io/blob/main/README.md#matployDraw) |
|<b>flaskFlow</b>|Genera una visualización de los datos.|[Saber mas...](https://github.com/oigonzalezp2024/oigonzalezp2024.github.io/blob/main/README.md#flaskFlow)|

Este proyecto está actualmente en desarrollo, pero contará siempre con una versión de demostración estable basada en estructuras de datos; primero solucionaremos los problemas de flujo y luego nos preocuparemos por el diseño y desarrollo de una base datos.  Su documentación lo llevará a conocer el estado actual del proyecto.  Todo desarrollador está invitado a participar si lo desea, es software libre.
[https://github.com/oigonzalezp2024/python-software-architecture](https://github.com/oigonzalezp2024/python-software-architecture/blob/main/README.md)

## cliente-webservice-sipsa
Cliente - Webservice SIPSA desarrollado en Python 3.8 usando la librería Zeep para acceder al servicio SOAP de la Webservice SIPSA mendiante la WSDL que aparece en el servicio web para consulta de la base de datos de sipsa.  
[https://github.com/oigonzalezp2024/cliente-webservice-sipsa](https://github.com/oigonzalezp2024/cliente-webservice-sipsa/blob/main/README.md)

## delimitedData
Delimita la data en formato json por periodos de tiempo.  
[https://github.com/oigonzalezp2024/delimitedData](https://github.com/oigonzalezp2024/delimitedData/blob/main/README.md)

## TransformData  
TransformData es una clase desarrollada para la transformación de datos sueltos en información útil para la toma de decisiones. Este código está destinado a ser modificado de acuerdo a una determinada lógica de modelo de negocio en constante evolución.
[https://github.com/oigonzalezp2024/transformData](https://github.com/oigonzalezp2024/transformData/blob/main/README.md)

## StagesController  
StagesController establece la configuración DDL mínina de una tabla de base de una datos MySQL destino, a partir de una estructura Json que se haya generado a partir de la consulta a una base de datos origen; Crea el diseño de las tablas necesarias para el diseño de la base de datos destino en:"./bbdd/ddl.sql". 
Después de establecer la configuración de las tablas, crea las tablas en la base de datos destino y ejecuta las consultas desarrolladas por el mismo, todo de forma automática, y finalmente, pobla la tabla de la base de datos destino, a partir del diseño de una consulta que el mismo haya configurado, terminando así un proceso de integración básico.  
[https://github.com/oigonzalezp2024/stagesController](https://github.com/oigonzalezp2024/stagesController/blob/main/README.md)

## matployDraw
MatployDraw aplica la librería matploy para la generación de gráficos estadísticos masivos a partir de la lectura de datos en formato json. Tan solo nos pide la ubicación de los datos Json ("/data/data.json") y la ruta donde queremos que se guarden los gráficos a generar.  
[https://github.com/oigonzalezp2024/matplotDraw/](https://github.com/oigonzalezp2024/matplotDraw/blob/main/README.md)

## flaskFlow
FlaskFlow es una clase que hereda de la librería Flask. He aplicado herencia a la clase Flask, para que por medio de este proyecto se pueda agregar o simplificar procesos de la misma, y al mismo tiempo tener acceso a todas herramientas presentes y futuras de Flask.  
[https://github.com/oigonzalezp2024/flaskFlow](https://github.com/oigonzalezp2024/flaskFlow/blob/main/README.md)

## python-fpdf2-table
PdfTable facilita la creación de tablas de datos en PDFs con python usando la librería FPDF2.  
[https://github.com/oigonzalezp2024/python-fpdf2-table](https://github.com/oigonzalezp2024/python-fpdf2-table/blob/main/README.md)

## python-fpdf2-pie-chart
PieChart facilita la creación de gráficos circulares en PDFs con python usando la librería FPDF2.  
[https://github.com/oigonzalezp2024/python-fpdf2-pie-chart](https://github.com/oigonzalezp2024/python-fpdf2-pie-chart/blob/main/README.md)

## petstore3-server-python-swagger
Base preconfigurada para crear un servidor Python Flask con Swagger.  
[https://github.com/oigonzalezp2024/petstore3-server-python-swagger](https://github.com/oigonzalezp2024/petstore3-server-python-swagger/blob/main/README.md)

## petstore3-client-python-swagger
El presente proyecto es un cliente de la API Petstore3, que es la herramienta demostrativa que Swagger utiliza para exponer el potencial de sus funcionalidades de servicios web.  
[https://github.com/oigonzalezp2024/petstore3-client-python-swagger](https://github.com/oigonzalezp2024/petstore3-client-python-swagger/blob/main/README.md)

## gopyFlow
GopyFlow reune todo el potencial de la clase Translator de la API de traducción de texto de googletrans con las poderosas herramientas de pytesseract de reconocimiento de texto en imágenes. Con su método gopy, permite extraer textos en inglés de una lista de imágenes, los traduce al español y los guarda como contenido unitario, tanto el texto detectado como su respectiva traducción, cuyo título acompañante hace referencia al nombre de la imágen gestionada.  
[https://github.com/oigonzalezp2024/gopyFLow](https://github.com/oigonzalezp2024/gopyFLow/blob/main/README.md)

## pyAudioFlow
PyAudioFlow es una clase que hereda de la librería PyAudio. He aplicado herencia a la clase de PyAudio, para que por medio de este proyecto se pueda agregar o simplificar procesos de la misma, y al mismo tiempo tener acceso a todas herramientas presentes y futuras de PyAudio.  
[https://github.com/oigonzalezp2024/pyAudioFlow](https://github.com/oigonzalezp2024/pyAudioFlow/blob/main/README.md)

## Conocimiento en lenguajes de programación:
- Python
- PHP
- C
- C++
- C#
- Javascript
- Java.

## Manejo base de datos
- MySQL
- SQL Server
- Oracle
- PostgreSQL

--- 
## Notas:

### Sobre la Webservice SIPSA del DANE
El servicio <b>SOAP</b> de la <b>Webservice del DANE</b> no tiene métodos (<b>Endpoints</b>) que permitan delimitar la consulta a la base de datos, se trae todos los datos registrados desde el 2020. La base de datos de la <b>Webservice de SIPSA</b> se ve obligada a extraer mas datos de los que el cliente puede que necesite.

Mientras este problema persista, del lado del cliente, existirá la necesidad de filtrar los datos mas de lo necesario. Lo ideal es que el cliente decida cual es el rango de tiempo de su interés en el mismo momento que realiza la consulta, con ello se solventaría este problema del lado del servidor. Hasta que no se solucione este problema, esto seguirá generando costos de tiempo de ejecución y procesamiento de datos, tanto del lado del servidor como del cliente.

[volver...](https://github.com/oigonzalezp2024/oigonzalezp2024.github.io/blob/main/README.md#python-software-architecture)

## Mas información:  
https://oigonzalezp2024.github.io/
