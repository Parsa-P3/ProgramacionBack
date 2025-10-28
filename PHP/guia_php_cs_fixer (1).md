# Guía de configuración de PHP CS Fixer en VS Code con XAMPP (Windows)

## 1. Instalar PHP CS Fixer

1.  Abre **PowerShell** o **CMD**.

2.  Ve a la carpeta de PHP de XAMPP:

    ``` bash
    cd C:\xampp\php
    ```

3.  Descarga PHP CS Fixer:

    ``` bash
    php -r "copy('https://cs.symfony.com/download/php-cs-fixer-v3.phar', 'php-cs-fixer.phar');"
    ```

4.  Crea un script `.bat` para ejecutarlo como comando:

    ``` bash
    echo @php "%~dp0php-cs-fixer.phar" %%* > php-cs-fixer.bat
    ```

Con esto tendrás el comando **php-cs-fixer** disponible en consola.

Prueba con:

``` bash
php-cs-fixer -V
```

Debería mostrar la versión.

------------------------------------------------------------------------

## 2. Crear archivo de configuración en tu proyecto

En la raíz del proyecto (donde están tus `ejercicio1.php`,
`ejercicio2.php`, etc.), crea el archivo:

**.php-cs-fixer.php**

Contenido recomendado:

``` php
<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->name('*.php')
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'indentation_type' => true,
        'braces_position' => [
            'classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
            'functions_opening_brace' => 'next_line_unless_newline_at_signature_end',
            'control_structures_opening_brace' => 'same_line',
        ],
    ])
    ->setFinder($finder);
```

------------------------------------------------------------------------

## 3. Configurar VS Code

Abre `settings.json` (**Ctrl+Shift+P → "settings json" → User Settings
JSON**) y agrega:

``` json
{
    "[php]": {
        "editor.defaultFormatter": "junstyle.php-cs-fixer",
        "editor.formatOnSave": true
    },
    "editor.tabSize": 4,
    "editor.insertSpaces": true
}
```

Esto asegura: - **php-cs-fixer** se ejecuta al guardar. - Tabulación con
**4 espacios** (estándar PSR-12).

------------------------------------------------------------------------

## 4. Probar en consola

Antes de usar VS Code, prueba en consola dentro de tu proyecto:

``` bash
php-cs-fixer fix ejercicio1.php --dry-run --diff
```

👉 Si ves un diff con cambios, el formateo funciona.

Para aplicar directamente:

``` bash
php-cs-fixer fix ejercicio1.php
```

------------------------------------------------------------------------

## 5. Probar en VS Code

1.  Abre `ejercicio1.php`.
2.  Escribe un `if` desordenado, por ejemplo:

``` php
if(true){
echo "hola";
}
```

3.  Guarda (**Ctrl+S**).\
    👉 Debería quedar así:

``` php
if (true) {
    echo "hola";
}
```

------------------------------------------------------------------------

✅ Con esto ya tienes **tabulación automática** y formateo completo en
VS Code para PHP.
