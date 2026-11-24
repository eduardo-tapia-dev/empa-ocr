# EMPA OCR — Digitalización de exámenes médicos preventivos

Plataforma web que digitaliza formularios EMPA (Examen Médico Preventivo del Adulto) en papel a partir de una foto o un PDF, extrae los datos con OCR y los deja en una base de datos para su análisis.

Proyecto de título de Ingeniería Civil en Computación e Informática, Universidad Central de Chile (2025). Autores: **Eduardo Tapia** y **Daniel Muñoz**. Adjudicado con el Fondo Semilla de Innovación de la universidad.

## Qué hace

- Captura del formulario desde la cámara del navegador o carga de imagen/PDF, con mejora de imagen y corrección de perspectiva.
- Extracción de campos con **AWS Textract** (análisis de formularios).
- Recomendaciones personalizadas generadas con la **API de OpenAI**.
- Usuarios con roles por centro de salud y resumen del examen en PDF.
- Dashboard de **Power BI** embebido con indicadores generales, hábitos de salud y riesgo cardiovascular.

## Resultados

- 492 formularios reales digitalizados en operativos de salud.
- En pruebas con personal de un hospital: **58 segundos por examen** frente a 5 minutos de digitación manual (≈80 % menos).

## Stack

PHP 8 (arquitectura MVC) · MySQL / MariaDB · JavaScript · AWS SDK for PHP (Textract) · OpenAI API · Power BI

## Estructura

```
app/
  controllers/   lógica de cada pantalla
  models/        acceso a datos y cliente de Textract
  services/      integración con OpenAI
  views/         vistas
  core/          conexión a la base de datos
modulo_lectura/  lectura del formulario (archivo y cámara)
public/          punto de entrada
```

## Cómo ejecutarlo

1. `composer install`
2. Crear la base de datos `bd_empa_2` en MySQL/MariaDB y ajustar `app/core/conexion_be.php`.
3. Definir las variables de entorno `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY` y `OPENAI_API_KEY`.
4. Servir la carpeta con Apache/XAMPP y entrar a `public/`.

> Este repositorio no incluye datos de pacientes, formularios reales ni credenciales.
