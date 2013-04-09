<?php

/* WebProfilerBundle:Collector:memory.html.twig */
class __TwigTemplate_e96334a81e96f7c8a393d6b2375c9902 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("WebProfilerBundle:Profiler:layout.html.twig");

        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "WebProfilerBundle:Profiler:layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_toolbar($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        ob_start();
        // line 5
        echo "        <span>
            <img width=\"13\" height=\"28\" alt=\"Memory Usage\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAcBAMAAABITyhxAAAAJ1BMVEXNzc3///////////////////////8/Pz////////////+NjY0/Pz9lMO+OAAAADHRSTlMAABAgMDhAWXCvv9e8JUuyAAAAQ0lEQVQI12MQBAMBBmLpMwoMDAw6BxjOOABpHyCdAKRzsNDp5eXl1KBh5oHBAYY9YHoDQ+cqIFjZwGCaBgSpBrjcCwCZgkUHKKvX+wAAAABJRU5ErkJggg==\"/>
            <span>";
        // line 7
        echo twig_escape_filter($this->env, sprintf("%.1f", (($this->getAttribute($this->getContext($context, "collector"), "memory") / 1024) / 1024)), "html", null, true);
        echo " MB</span>
        </span>
    ";
        $context["icon"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 10
        echo "    ";
        ob_start();
        // line 11
        echo "        <div class=\"sf-toolbar-info-piece\">
            <b>Memory usage</b>
            <span>";
        // line 13
        echo twig_escape_filter($this->env, sprintf("%.1f", (($this->getAttribute($this->getContext($context, "collector"), "memory") / 1024) / 1024)), "html", null, true);
        echo " MB</span>
        </div>
    ";
        $context["text"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 16
        echo "    ";
        $this->env->loadTemplate("WebProfilerBundle:Profiler:toolbar_item.html.twig")->display(array_merge($context, array("link" => false)));
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:memory.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  57 => 16,  34 => 5,  31 => 4,  786 => 466,  783 => 465,  772 => 463,  768 => 462,  764 => 460,  751 => 459,  725 => 454,  722 => 453,  703 => 451,  686 => 450,  682 => 448,  678 => 447,  674 => 446,  670 => 445,  666 => 444,  662 => 443,  659 => 442,  657 => 441,  640 => 440,  629 => 439,  614 => 434,  609 => 432,  605 => 431,  602 => 430,  588 => 429,  556 => 399,  538 => 396,  521 => 395,  518 => 394,  516 => 393,  511 => 391,  506 => 389,  250 => 136,  194 => 90,  179 => 85,  161 => 79,  159 => 78,  146 => 72,  142 => 71,  137 => 69,  124 => 59,  117 => 55,  99 => 43,  85 => 38,  53 => 22,  45 => 19,  28 => 3,  205 => 93,  178 => 62,  176 => 84,  170 => 60,  160 => 59,  132 => 47,  102 => 34,  90 => 28,  81 => 24,  204 => 71,  191 => 70,  185 => 68,  167 => 64,  164 => 80,  153 => 62,  147 => 58,  138 => 55,  134 => 54,  127 => 52,  122 => 51,  95 => 31,  91 => 40,  87 => 34,  84 => 25,  49 => 13,  27 => 3,  77 => 33,  71 => 31,  68 => 19,  62 => 16,  58 => 16,  56 => 23,  44 => 10,  388 => 160,  385 => 159,  379 => 158,  377 => 157,  370 => 156,  366 => 155,  362 => 153,  360 => 152,  357 => 151,  354 => 150,  352 => 149,  344 => 147,  342 => 146,  339 => 145,  330 => 140,  327 => 139,  320 => 135,  314 => 131,  311 => 130,  308 => 129,  306 => 128,  301 => 125,  292 => 120,  289 => 119,  287 => 118,  282 => 115,  280 => 114,  275 => 111,  273 => 110,  268 => 107,  264 => 105,  258 => 103,  254 => 101,  247 => 97,  240 => 93,  234 => 89,  231 => 88,  226 => 86,  221 => 83,  215 => 79,  212 => 78,  209 => 73,  207 => 76,  202 => 92,  196 => 69,  193 => 68,  190 => 67,  188 => 87,  183 => 63,  177 => 59,  174 => 67,  171 => 83,  169 => 82,  162 => 53,  143 => 56,  130 => 53,  107 => 49,  103 => 25,  97 => 23,  88 => 39,  82 => 19,  79 => 23,  76 => 17,  73 => 16,  67 => 23,  61 => 12,  47 => 11,  36 => 16,  70 => 24,  63 => 16,  46 => 12,  39 => 17,  22 => 1,  163 => 32,  155 => 50,  152 => 49,  149 => 48,  145 => 57,  139 => 45,  123 => 35,  120 => 50,  115 => 44,  111 => 38,  108 => 41,  106 => 36,  101 => 33,  98 => 32,  96 => 31,  92 => 21,  80 => 32,  74 => 32,  64 => 19,  55 => 9,  52 => 14,  50 => 10,  43 => 9,  41 => 8,  37 => 8,  32 => 5,  29 => 6,  356 => 163,  347 => 160,  343 => 159,  340 => 158,  335 => 157,  333 => 141,  325 => 138,  323 => 149,  316 => 145,  309 => 141,  302 => 137,  295 => 121,  288 => 129,  281 => 125,  274 => 121,  259 => 109,  252 => 100,  245 => 96,  238 => 97,  228 => 87,  225 => 88,  217 => 83,  214 => 82,  211 => 81,  206 => 78,  203 => 77,  198 => 69,  192 => 72,  184 => 70,  182 => 86,  172 => 64,  165 => 54,  158 => 56,  154 => 75,  151 => 47,  148 => 73,  140 => 42,  135 => 47,  131 => 42,  128 => 45,  125 => 44,  119 => 45,  116 => 31,  113 => 40,  110 => 50,  104 => 35,  100 => 24,  93 => 41,  89 => 29,  86 => 29,  83 => 28,  75 => 23,  72 => 22,  69 => 20,  66 => 28,  60 => 25,  54 => 12,  51 => 13,  48 => 20,  42 => 18,  38 => 7,  35 => 6,  33 => 4,  30 => 4,);
    }
}
