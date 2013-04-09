<?php

/* WebProfilerBundle:Router:panel.html.twig */
class __TwigTemplate_d5d07261686096c5e66a598ae831c12f extends Twig_Template
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
        echo "<h2>Routing for \"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "request"), "pathinfo"), "html", null, true);
        echo "\"</h2>

<ul>
    <li>
        <strong>Route:&nbsp;</strong>
        ";
        // line 6
        if ($this->getAttribute($this->getContext($context, "request"), "route")) {
            // line 7
            echo "            ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "request"), "route"), "html", null, true);
            echo "
        ";
        } else {
            // line 9
            echo "            <em>No matching route</em>
        ";
        }
        // line 11
        echo "    </li>
    <li>
        <strong>Route parameters:&nbsp;</strong>
        ";
        // line 14
        if (twig_length_filter($this->env, $this->getAttribute($this->getContext($context, "request"), "routeParams"))) {
            // line 15
            echo "            ";
            $this->env->loadTemplate("@WebProfiler/Profiler/table.html.twig")->display(array("data" => $this->getAttribute($this->getContext($context, "request"), "routeParams"), "class" => "inline"));
            // line 16
            echo "        ";
        } else {
            // line 17
            echo "            <em>No parameters</em>
        ";
        }
        // line 19
        echo "    </li>
    ";
        // line 20
        if ($this->getAttribute($this->getContext($context, "router"), "redirect")) {
            // line 21
            echo "    <li>
        <strong>Redirecting to:&nbsp;</strong> \"";
            // line 22
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "router"), "targetUrl"), "html", null, true);
            echo "\" ";
            if ($this->getAttribute($this->getContext($context, "router"), "targetRoute")) {
                echo "(route: \"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "router"), "targetRoute"), "html", null, true);
                echo "\")";
            }
            // line 23
            echo "    <li>
    ";
        }
        // line 25
        echo "    <li>
        <strong>Route matching logs</strong>
        <table class=\"routing inline\">
            <tr>
                <th>Route name</th>
                <th>Pattern</th>
                <th>Log</th>
            </tr>
            ";
        // line 33
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "traces"));
        foreach ($context['_seq'] as $context["_key"] => $context["trace"]) {
            // line 34
            echo "                <tr class=\"";
            echo (((1 == $this->getAttribute($this->getContext($context, "trace"), "level"))) ? ("almost") : ((((2 == $this->getAttribute($this->getContext($context, "trace"), "level"))) ? ("matches") : (""))));
            echo "\">
                    <td>";
            // line 35
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "trace"), "name"), "html", null, true);
            echo "</td>
                    <td>";
            // line 36
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "trace"), "path"), "html", null, true);
            echo "</td>
                    <td>";
            // line 37
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "trace"), "log"), "html", null, true);
            echo "</td>
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['trace'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 40
        echo "        </table>
        <em><small>Note: The above matching is based on the configuration for the current router which might differ from
        the configuration used while routing this request.</small></em>
    </li>
</ul>
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Router:panel.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  791 => 473,  788 => 472,  777 => 470,  773 => 469,  769 => 467,  756 => 466,  730 => 461,  727 => 460,  708 => 458,  691 => 457,  687 => 455,  683 => 454,  679 => 453,  671 => 451,  667 => 450,  663 => 449,  660 => 448,  658 => 447,  641 => 446,  615 => 440,  606 => 437,  603 => 436,  601 => 435,  532 => 396,  515 => 395,  510 => 393,  385 => 160,  382 => 159,  363 => 155,  349 => 149,  317 => 135,  311 => 131,  308 => 130,  292 => 121,  237 => 93,  225 => 87,  204 => 76,  180 => 63,  396 => 179,  378 => 170,  376 => 158,  369 => 165,  348 => 153,  334 => 145,  293 => 118,  219 => 84,  195 => 71,  344 => 119,  300 => 105,  153 => 77,  388 => 177,  346 => 136,  323 => 124,  319 => 123,  315 => 110,  212 => 79,  202 => 75,  643 => 389,  634 => 383,  630 => 445,  628 => 377,  618 => 374,  597 => 361,  593 => 360,  587 => 434,  567 => 346,  559 => 341,  535 => 326,  504 => 306,  492 => 299,  463 => 284,  458 => 282,  446 => 275,  428 => 265,  420 => 260,  390 => 155,  354 => 151,  281 => 167,  222 => 138,  217 => 136,  297 => 104,  291 => 102,  256 => 122,  251 => 101,  227 => 105,  221 => 85,  215 => 76,  210 => 97,  207 => 129,  194 => 89,  188 => 90,  161 => 63,  129 => 55,  333 => 85,  330 => 141,  325 => 82,  299 => 75,  290 => 72,  288 => 116,  270 => 110,  267 => 99,  261 => 105,  244 => 136,  239 => 86,  231 => 89,  213 => 23,  170 => 84,  159 => 53,  150 => 55,  124 => 44,  113 => 40,  12 => 45,  631 => 203,  625 => 201,  623 => 200,  617 => 196,  608 => 370,  604 => 368,  595 => 190,  591 => 189,  579 => 185,  571 => 183,  568 => 182,  565 => 181,  549 => 334,  545 => 97,  534 => 92,  529 => 321,  526 => 90,  514 => 79,  511 => 310,  505 => 391,  501 => 88,  496 => 301,  493 => 76,  489 => 171,  459 => 159,  442 => 155,  434 => 153,  431 => 152,  415 => 147,  410 => 253,  394 => 242,  332 => 116,  301 => 76,  284 => 118,  265 => 107,  262 => 104,  257 => 74,  249 => 100,  185 => 66,  174 => 59,  613 => 372,  610 => 438,  605 => 199,  598 => 191,  592 => 192,  588 => 190,  586 => 187,  583 => 356,  577 => 353,  564 => 181,  561 => 180,  553 => 177,  550 => 399,  544 => 174,  542 => 330,  539 => 172,  536 => 171,  531 => 324,  527 => 320,  507 => 102,  502 => 122,  499 => 78,  494 => 120,  491 => 119,  485 => 292,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 252,  403 => 84,  397 => 48,  389 => 137,  370 => 229,  367 => 156,  361 => 127,  357 => 152,  351 => 150,  347 => 124,  342 => 134,  339 => 146,  336 => 145,  327 => 140,  320 => 171,  316 => 80,  313 => 79,  307 => 166,  304 => 165,  296 => 74,  253 => 147,  248 => 96,  242 => 96,  234 => 140,  232 => 139,  226 => 81,  205 => 95,  198 => 132,  179 => 63,  155 => 68,  127 => 54,  335 => 94,  326 => 90,  306 => 107,  303 => 128,  279 => 115,  276 => 113,  273 => 112,  271 => 111,  259 => 103,  255 => 103,  245 => 66,  240 => 92,  218 => 83,  211 => 53,  206 => 77,  190 => 68,  181 => 115,  172 => 62,  167 => 73,  134 => 47,  65 => 22,  63 => 18,  686 => 206,  680 => 203,  677 => 202,  675 => 452,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 205,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 100,  555 => 178,  538 => 93,  521 => 317,  517 => 163,  512 => 394,  509 => 161,  506 => 160,  503 => 159,  500 => 389,  498 => 157,  495 => 156,  486 => 151,  482 => 166,  477 => 147,  475 => 146,  472 => 287,  468 => 286,  462 => 123,  456 => 158,  450 => 157,  443 => 140,  432 => 136,  426 => 151,  421 => 131,  416 => 129,  405 => 127,  400 => 180,  391 => 138,  386 => 176,  380 => 112,  377 => 148,  375 => 231,  371 => 145,  366 => 107,  359 => 153,  356 => 139,  353 => 138,  343 => 122,  340 => 97,  331 => 92,  329 => 127,  324 => 139,  321 => 112,  318 => 111,  310 => 184,  302 => 116,  289 => 120,  286 => 119,  282 => 69,  277 => 114,  274 => 97,  272 => 111,  269 => 75,  263 => 95,  254 => 92,  250 => 58,  243 => 93,  238 => 64,  236 => 50,  233 => 84,  228 => 88,  223 => 86,  200 => 92,  197 => 60,  191 => 69,  184 => 114,  178 => 66,  175 => 65,  146 => 64,  118 => 49,  110 => 51,  104 => 37,  90 => 42,  81 => 23,  59 => 14,  23 => 18,  76 => 34,  58 => 25,  53 => 17,  20 => 1,  152 => 67,  148 => 46,  137 => 88,  102 => 33,  100 => 36,  97 => 23,  77 => 25,  34 => 5,  480 => 290,  474 => 164,  469 => 162,  461 => 160,  457 => 153,  453 => 101,  444 => 98,  440 => 272,  437 => 138,  435 => 270,  430 => 94,  427 => 143,  423 => 149,  413 => 146,  409 => 183,  407 => 131,  402 => 143,  398 => 243,  393 => 178,  387 => 122,  384 => 237,  381 => 150,  379 => 236,  374 => 157,  368 => 112,  365 => 142,  362 => 161,  360 => 140,  355 => 157,  341 => 147,  337 => 96,  322 => 138,  314 => 99,  312 => 129,  309 => 108,  305 => 129,  298 => 125,  294 => 136,  285 => 115,  283 => 100,  278 => 98,  268 => 104,  264 => 98,  258 => 94,  252 => 59,  247 => 117,  241 => 149,  235 => 89,  229 => 87,  224 => 81,  220 => 63,  214 => 69,  208 => 73,  169 => 50,  143 => 43,  140 => 42,  132 => 59,  128 => 55,  119 => 40,  107 => 27,  71 => 30,  38 => 18,  177 => 64,  165 => 83,  160 => 23,  135 => 58,  126 => 56,  114 => 23,  84 => 40,  70 => 15,  67 => 14,  61 => 12,  94 => 21,  89 => 40,  85 => 24,  75 => 19,  68 => 30,  56 => 28,  196 => 92,  183 => 84,  171 => 58,  166 => 56,  163 => 82,  158 => 80,  156 => 58,  151 => 47,  142 => 63,  138 => 61,  136 => 71,  121 => 50,  117 => 39,  105 => 34,  91 => 34,  62 => 21,  49 => 14,  87 => 33,  21 => 12,  31 => 4,  25 => 14,  26 => 2,  28 => 6,  24 => 14,  19 => 1,  93 => 43,  88 => 20,  78 => 29,  46 => 13,  44 => 20,  27 => 3,  79 => 18,  72 => 18,  69 => 17,  47 => 15,  40 => 11,  37 => 18,  22 => 12,  246 => 70,  157 => 21,  145 => 74,  139 => 49,  131 => 45,  123 => 61,  120 => 53,  115 => 75,  111 => 47,  108 => 47,  101 => 31,  98 => 45,  96 => 35,  83 => 33,  74 => 31,  66 => 32,  55 => 24,  52 => 12,  50 => 16,  43 => 9,  41 => 19,  35 => 5,  32 => 6,  29 => 3,  209 => 78,  203 => 128,  199 => 93,  193 => 69,  189 => 71,  187 => 67,  182 => 87,  176 => 86,  173 => 85,  168 => 57,  164 => 59,  162 => 54,  154 => 60,  149 => 53,  147 => 75,  144 => 92,  141 => 73,  133 => 47,  130 => 39,  125 => 51,  122 => 41,  116 => 57,  112 => 36,  109 => 52,  106 => 51,  103 => 25,  99 => 31,  95 => 64,  92 => 43,  86 => 40,  82 => 19,  80 => 27,  73 => 23,  64 => 17,  60 => 20,  57 => 19,  54 => 12,  51 => 13,  48 => 21,  45 => 14,  42 => 7,  39 => 6,  36 => 9,  33 => 4,  30 => 7,);
    }
}
