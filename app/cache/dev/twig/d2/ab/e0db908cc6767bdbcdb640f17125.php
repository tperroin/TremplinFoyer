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
        return array (  91 => 20,  84 => 19,  74 => 16,  55 => 13,  44 => 13,  25 => 4,  105 => 24,  98 => 22,  96 => 21,  93 => 20,  89 => 19,  83 => 18,  76 => 16,  72 => 14,  68 => 12,  50 => 8,  33 => 5,  27 => 4,  24 => 2,  22 => 2,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 78,  186 => 76,  180 => 72,  178 => 71,  172 => 67,  163 => 63,  159 => 61,  154 => 59,  147 => 55,  143 => 54,  136 => 50,  130 => 47,  127 => 46,  121 => 45,  118 => 44,  114 => 43,  104 => 36,  100 => 34,  95 => 31,  78 => 28,  63 => 24,  34 => 11,  19 => 1,  94 => 39,  88 => 6,  81 => 40,  79 => 17,  35 => 7,  21 => 2,  32 => 4,  29 => 3,  224 => 72,  206 => 55,  203 => 54,  193 => 47,  189 => 77,  185 => 45,  181 => 44,  174 => 41,  164 => 34,  157 => 60,  150 => 21,  146 => 20,  141 => 19,  138 => 51,  132 => 48,  128 => 14,  124 => 13,  119 => 12,  116 => 11,  110 => 9,  97 => 91,  77 => 73,  75 => 27,  71 => 26,  69 => 54,  66 => 15,  64 => 41,  59 => 22,  57 => 14,  48 => 19,  46 => 7,  41 => 9,  36 => 7,  26 => 3,  80 => 31,  67 => 24,  60 => 23,  58 => 9,  51 => 12,  43 => 7,  39 => 6,  31 => 6,  28 => 2,);
    }
}
