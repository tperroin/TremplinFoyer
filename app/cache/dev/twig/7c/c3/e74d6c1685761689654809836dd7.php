<?php

/* TperroinTremplinFoyerBundle:Helper:googlePlusButton.html.twig */
class __TwigTemplate_7cc3e74d6c1685761689654809836dd7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        if (((!array_key_exists("url", $context)) || ($this->getContext($context, "url") == null))) {
            // line 2
            echo "    ";
            $context["url"] = $this->getAttribute($this->getAttribute($this->getContext($context, "app"), "request"), "uri");
        }
        // line 4
        echo " 
";
        // line 5
        ob_start();
        // line 6
        echo " <div class=\"g-plusone\" data-size=\"";
        echo twig_escape_filter($this->env, $this->getContext($context, "size"), "html", null, true);
        echo "\" data-annotation=\"";
        echo twig_escape_filter($this->env, $this->getContext($context, "annotation"), "html", null, true);
        echo "\" data-width=\"";
        echo twig_escape_filter($this->env, $this->getContext($context, "width"), "html", null, true);
        echo "\" data-href=\"";
        echo twig_escape_filter($this->env, $this->getContext($context, "url"), "html", null, true);
        echo "\"></div>
 
 <script type=\"text/javascript\">
   window.___gcfg = {lang: '";
        // line 9
        echo twig_escape_filter($this->env, $this->getContext($context, "locale"), "html", null, true);
        echo "'};
 
   (function() {
     var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
     po.src = 'https://apis.google.com/js/plusone.js';
     var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
   })();
 </script>
 
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    public function getTemplateName()
    {
        return "TperroinTremplinFoyerBundle:Helper:googlePlusButton.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 16,  39 => 12,  30 => 6,  25 => 4,  21 => 2,  55 => 16,  52 => 15,  47 => 13,  41 => 10,  36 => 8,  33 => 7,  27 => 4,  24 => 3,  22 => 2,  19 => 1,  285 => 106,  279 => 97,  274 => 96,  271 => 95,  266 => 73,  248 => 55,  245 => 54,  236 => 48,  232 => 47,  228 => 46,  224 => 45,  220 => 44,  213 => 41,  203 => 34,  196 => 31,  189 => 21,  185 => 20,  180 => 19,  177 => 18,  171 => 15,  167 => 14,  163 => 13,  158 => 12,  155 => 11,  149 => 9,  136 => 125,  116 => 107,  114 => 106,  105 => 99,  103 => 95,  73 => 70,  71 => 54,  68 => 53,  66 => 41,  61 => 19,  50 => 14,  48 => 18,  45 => 17,  43 => 9,  28 => 5,  83 => 35,  80 => 74,  78 => 73,  74 => 32,  69 => 29,  65 => 28,  62 => 27,  59 => 31,  38 => 9,  32 => 7,  29 => 3,);
    }
}
