<?php

/* WebProfilerBundle:Profiler:toolbar_js.html.twig */
class __TwigTemplate_d2abe0db908cc6767bdbcdb640f17125 extends Twig_Template
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
        echo "<div id=\"sfwdt";
        echo twig_escape_filter($this->env, $this->getContext($context, "token"), "html", null, true);
        echo "\" class=\"sf-toolbar\" style=\"display: none\"></div>
";
        // line 2
        $this->env->loadTemplate("WebProfilerBundle:Profiler:base_js.html.twig")->display($context);
        // line 3
        echo "<script type=\"text/javascript\">/*<![CDATA[*/
    (function () {
        Sfjs.load(
            'sfwdt";
        // line 6
        echo twig_escape_filter($this->env, $this->getContext($context, "token"), "html", null, true);
        echo "',
            '";
        // line 7
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_wdt", array("token" => $this->getContext($context, "token"))), "html", null, true);
        echo "',
            function(xhr, el) {
                el.style.display = -1 !== xhr.responseText.indexOf('sf-toolbarreset') ? 'block' : 'none';
            },
            function(xhr) {
                if (xhr.status !== 0) {
                    confirm('An error occurred while loading the web debug toolbar (' + xhr.status + ': ' + xhr.statusText + ').\\n\\nDo you want to open the profiler?') && (window.location = '";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_profiler", array("token" => $this->getContext($context, "token"))), "html", null, true);
        echo "');
                }
            }
        );
    })();
/*]]>*/</script>
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Profiler:toolbar_js.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  44 => 13,  35 => 7,  31 => 6,  26 => 3,  60 => 17,  58 => 16,  49 => 13,  42 => 10,  34 => 8,  46 => 16,  39 => 12,  30 => 6,  25 => 4,  21 => 2,  55 => 15,  52 => 15,  47 => 12,  41 => 10,  36 => 8,  33 => 7,  27 => 4,  24 => 2,  22 => 12,  19 => 1,  285 => 106,  279 => 97,  274 => 96,  271 => 95,  266 => 73,  248 => 55,  245 => 54,  236 => 48,  232 => 47,  228 => 46,  224 => 45,  220 => 44,  213 => 41,  203 => 34,  196 => 31,  189 => 21,  185 => 20,  180 => 19,  177 => 18,  171 => 15,  167 => 14,  163 => 13,  158 => 12,  155 => 11,  149 => 9,  136 => 125,  116 => 107,  114 => 106,  105 => 99,  103 => 95,  73 => 70,  71 => 54,  68 => 53,  66 => 19,  61 => 19,  50 => 14,  48 => 18,  45 => 17,  43 => 9,  28 => 5,  83 => 35,  80 => 74,  78 => 73,  74 => 32,  69 => 29,  65 => 28,  62 => 27,  59 => 31,  38 => 9,  32 => 7,  29 => 3,);
    }
}
