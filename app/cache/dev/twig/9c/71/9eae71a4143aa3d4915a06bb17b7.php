<?php

/* WebProfilerBundle:Profiler:toolbar.html.twig */
class __TwigTemplate_9c719eae71a4143aa3d4915a06bb17b7 extends Twig_Template
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
        echo "<!-- START of Symfony2 Web Debug Toolbar -->
";
        // line 2
        if (("normal" != $this->getContext($context, "position"))) {
            // line 3
            echo "    ";
            $this->env->loadTemplate("WebProfilerBundle:Profiler:toolbar_style.html.twig")->display(array_merge($context, array("position" => $this->getContext($context, "position"), "floatable" => true)));
            // line 4
            echo "    <div style=\"clear: both; height: 38px;\"></div>
";
        }
        // line 6
        echo "
<div class=\"sf-toolbarreset\">
    ";
        // line 8
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "templates"));
        foreach ($context['_seq'] as $context["name"] => $context["template"]) {
            // line 9
            echo "        ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "template"), "renderblock", array(0 => "toolbar", 1 => array("collector" => $this->getAttribute($this->getContext($context, "profile"), "getcollector", array(0 => $this->getContext($context, "name")), "method"), "profiler_url" => $this->getContext($context, "profiler_url"), "token" => $this->getAttribute($this->getContext($context, "profile"), "token"), "name" => $this->getContext($context, "name"))), "method"), "html", null, true);
            // line 15
            echo "
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['name'], $context['template'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 17
        echo "
    ";
        // line 18
        if (("normal" != $this->getContext($context, "position"))) {
            // line 19
            echo "        <a class=\"hide-button\" title=\"Close Toolbar\" onclick=\"
            var p = this.parentNode;
            p.style.display = 'none';
            (p.previousElementSibling || p.previousSibling).style.display = 'none';
        \"></a>
    ";
        }
        // line 25
        echo "</div>
<!-- END of Symfony2 Web Debug Toolbar -->
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Profiler:toolbar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  24 => 3,  19 => 1,  173 => 70,  156 => 65,  94 => 38,  479 => 162,  473 => 161,  468 => 158,  460 => 155,  456 => 153,  452 => 151,  443 => 149,  439 => 148,  436 => 147,  434 => 146,  429 => 144,  426 => 143,  422 => 142,  412 => 134,  408 => 132,  406 => 131,  401 => 130,  397 => 129,  392 => 126,  386 => 122,  383 => 121,  380 => 120,  378 => 119,  373 => 116,  367 => 112,  364 => 111,  361 => 110,  359 => 109,  336 => 103,  321 => 101,  313 => 99,  304 => 95,  297 => 91,  293 => 90,  284 => 89,  277 => 86,  267 => 85,  263 => 84,  257 => 81,  251 => 80,  246 => 78,  223 => 71,  219 => 70,  213 => 69,  181 => 66,  168 => 60,  144 => 62,  141 => 61,  126 => 51,  114 => 42,  109 => 44,  78 => 21,  157 => 56,  136 => 44,  133 => 43,  57 => 11,  34 => 4,  31 => 6,  786 => 466,  783 => 465,  772 => 463,  768 => 462,  764 => 460,  751 => 459,  725 => 454,  722 => 453,  703 => 451,  686 => 450,  682 => 448,  678 => 447,  674 => 446,  670 => 445,  666 => 444,  662 => 443,  659 => 442,  657 => 441,  640 => 440,  629 => 439,  614 => 434,  609 => 432,  605 => 431,  602 => 430,  588 => 429,  556 => 399,  538 => 396,  521 => 395,  518 => 394,  516 => 393,  511 => 391,  506 => 389,  250 => 136,  194 => 90,  179 => 85,  161 => 79,  159 => 78,  146 => 58,  142 => 56,  137 => 60,  124 => 59,  117 => 47,  99 => 43,  85 => 25,  53 => 22,  45 => 8,  28 => 3,  205 => 93,  178 => 66,  176 => 65,  170 => 61,  160 => 66,  132 => 47,  102 => 41,  90 => 28,  81 => 22,  204 => 71,  191 => 70,  185 => 68,  167 => 64,  164 => 67,  153 => 54,  147 => 55,  138 => 45,  134 => 54,  127 => 52,  122 => 43,  95 => 34,  91 => 40,  87 => 34,  84 => 28,  49 => 17,  27 => 4,  77 => 28,  71 => 17,  68 => 22,  62 => 25,  58 => 16,  56 => 23,  44 => 10,  388 => 160,  385 => 159,  379 => 158,  377 => 157,  370 => 156,  366 => 155,  362 => 153,  360 => 152,  357 => 151,  354 => 106,  352 => 149,  344 => 147,  342 => 146,  339 => 145,  330 => 140,  327 => 139,  320 => 135,  314 => 131,  311 => 98,  308 => 97,  306 => 128,  301 => 125,  292 => 120,  289 => 119,  287 => 118,  282 => 88,  280 => 114,  275 => 111,  273 => 110,  268 => 107,  264 => 105,  258 => 103,  254 => 101,  247 => 97,  240 => 77,  234 => 74,  231 => 88,  226 => 86,  221 => 83,  215 => 79,  212 => 78,  209 => 73,  207 => 68,  202 => 92,  196 => 69,  193 => 68,  190 => 67,  188 => 87,  183 => 63,  177 => 59,  174 => 67,  171 => 62,  169 => 82,  162 => 57,  143 => 48,  130 => 42,  107 => 36,  103 => 37,  97 => 39,  88 => 39,  82 => 19,  79 => 23,  76 => 17,  73 => 19,  67 => 15,  61 => 18,  47 => 9,  36 => 5,  70 => 20,  63 => 16,  46 => 7,  39 => 9,  22 => 2,  163 => 59,  155 => 58,  152 => 64,  149 => 48,  145 => 57,  139 => 55,  123 => 50,  120 => 50,  115 => 44,  111 => 45,  108 => 31,  106 => 36,  101 => 32,  98 => 31,  96 => 31,  92 => 33,  80 => 29,  74 => 32,  64 => 14,  55 => 9,  52 => 18,  50 => 10,  43 => 9,  41 => 8,  37 => 8,  32 => 4,  29 => 3,  356 => 163,  347 => 160,  343 => 159,  340 => 105,  335 => 157,  333 => 141,  325 => 138,  323 => 149,  316 => 145,  309 => 141,  302 => 137,  295 => 121,  288 => 129,  281 => 125,  274 => 121,  259 => 109,  252 => 100,  245 => 96,  238 => 97,  228 => 73,  225 => 88,  217 => 83,  214 => 82,  211 => 81,  206 => 78,  203 => 77,  198 => 67,  192 => 72,  184 => 70,  182 => 86,  172 => 64,  165 => 58,  158 => 56,  154 => 75,  151 => 53,  148 => 63,  140 => 42,  135 => 47,  131 => 51,  128 => 50,  125 => 44,  119 => 42,  116 => 35,  113 => 40,  110 => 50,  104 => 35,  100 => 40,  93 => 28,  89 => 26,  86 => 33,  83 => 28,  75 => 23,  72 => 22,  69 => 20,  66 => 28,  60 => 13,  54 => 19,  51 => 10,  48 => 8,  42 => 15,  38 => 6,  35 => 8,  33 => 4,  30 => 3,);
    }
}
