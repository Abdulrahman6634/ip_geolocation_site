<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* 404.twig */
class __TwigTemplate_d9f19148713ee41854955d66fd234e09 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        yield "<!doctype html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\"
          content=\"width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
    <title>404 Page Not Found</title>
    <style>
        @import url(\"https://fonts.googleapis.com/css?family=Montserrat:700\");
        .container {
            height: 100vh;
            font-family: \"Montserrat\", \"sans-serif\";
            font-weight: bolder;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            a {
                color: black;
            }
        }

    </style>
</head>
<body>
<div class=\"container\">
    <h1>404 Page Not Found.</h1>
    <h1> <span class=\"ascii\">(╯°□°）╯︵ ┻━┻</span></h1>
    <a href=\"/\">Go back</a>
</div>
</body>
</html>

";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "404.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array ();
    }

    public function getSourceContext()
    {
        return new Source("", "404.twig", "/home/abdul/Desktop/Website/store.local/views/404.twig");
    }
}
