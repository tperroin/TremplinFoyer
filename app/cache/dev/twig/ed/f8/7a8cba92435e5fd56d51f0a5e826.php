<?php

/* SonataAdminBundle:CRUD:list_array.html.twig */
class __TwigTemplate_edf87a8cba92435e5fd56d51f0a5e826 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'field' => array($this, 'block_field'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate($this->getAttribute($this->getContext($context, "admin"), "getTemplate", array(0 => "base_list_field"), "method"));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 14
    public function block_field($context, array $blocks = array())
    {
        // line 15
        echo "    ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "value"));
        foreach ($context['_seq'] as $context["key"] => $context["val"]) {
            // line 16
            echo "        [";
            echo twig_escape_filter($this->env, $this->getContext($context, "key"), "html", null, true);
            echo " => ";
            echo twig_escape_filter($this->env, $this->getContext($context, "val"), "html", null, true);
            echo "]
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['val'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:CRUD:list_array.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  613 => 205,  610 => 204,  605 => 199,  598 => 195,  592 => 192,  588 => 190,  586 => 189,  583 => 188,  577 => 186,  564 => 181,  561 => 180,  553 => 177,  550 => 176,  544 => 174,  542 => 173,  539 => 172,  536 => 171,  531 => 159,  527 => 128,  507 => 123,  502 => 122,  499 => 121,  494 => 120,  491 => 119,  485 => 110,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 85,  403 => 84,  397 => 48,  389 => 46,  370 => 40,  367 => 39,  361 => 36,  357 => 35,  351 => 32,  347 => 31,  342 => 29,  339 => 28,  336 => 27,  327 => 204,  320 => 171,  316 => 170,  313 => 169,  307 => 166,  304 => 165,  296 => 160,  253 => 147,  248 => 144,  242 => 142,  234 => 140,  232 => 139,  226 => 137,  205 => 135,  198 => 132,  179 => 111,  155 => 81,  127 => 52,  335 => 94,  326 => 90,  306 => 87,  303 => 86,  279 => 152,  276 => 151,  273 => 150,  271 => 79,  259 => 149,  255 => 69,  245 => 66,  240 => 64,  218 => 57,  211 => 53,  206 => 51,  190 => 50,  181 => 47,  172 => 44,  167 => 42,  134 => 26,  65 => 5,  63 => 17,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 191,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 168,  555 => 178,  538 => 165,  521 => 127,  517 => 163,  512 => 124,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 156,  486 => 151,  482 => 149,  477 => 147,  475 => 146,  472 => 145,  468 => 125,  462 => 123,  456 => 102,  450 => 119,  443 => 140,  432 => 136,  426 => 133,  421 => 131,  416 => 129,  405 => 127,  400 => 119,  391 => 118,  386 => 115,  380 => 112,  377 => 111,  375 => 41,  371 => 109,  366 => 107,  359 => 106,  356 => 105,  353 => 104,  343 => 98,  340 => 97,  331 => 92,  329 => 211,  324 => 92,  321 => 91,  318 => 90,  310 => 87,  302 => 164,  289 => 156,  286 => 85,  282 => 79,  277 => 78,  274 => 77,  272 => 76,  269 => 75,  263 => 71,  254 => 68,  250 => 67,  243 => 65,  238 => 64,  236 => 63,  233 => 62,  228 => 59,  223 => 58,  200 => 133,  197 => 54,  191 => 119,  184 => 114,  178 => 46,  175 => 45,  146 => 34,  118 => 28,  110 => 63,  104 => 23,  90 => 18,  81 => 15,  59 => 155,  23 => 12,  76 => 13,  58 => 38,  53 => 77,  20 => 11,  152 => 35,  148 => 63,  137 => 69,  102 => 41,  100 => 40,  97 => 42,  77 => 8,  34 => 16,  480 => 148,  474 => 161,  469 => 158,  461 => 155,  457 => 153,  453 => 101,  444 => 98,  440 => 148,  437 => 138,  435 => 137,  430 => 94,  427 => 143,  423 => 132,  413 => 134,  409 => 132,  407 => 131,  402 => 126,  398 => 129,  393 => 47,  387 => 122,  384 => 44,  381 => 120,  379 => 42,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 109,  355 => 106,  341 => 105,  337 => 96,  322 => 201,  314 => 99,  312 => 89,  309 => 97,  305 => 95,  298 => 84,  294 => 159,  285 => 154,  283 => 84,  278 => 86,  268 => 78,  264 => 84,  258 => 81,  252 => 80,  247 => 66,  241 => 77,  235 => 74,  229 => 138,  224 => 71,  220 => 70,  214 => 69,  208 => 136,  169 => 43,  143 => 71,  140 => 55,  132 => 51,  128 => 24,  119 => 50,  107 => 24,  71 => 19,  38 => 18,  177 => 65,  165 => 64,  160 => 66,  135 => 47,  126 => 51,  114 => 42,  84 => 16,  70 => 20,  67 => 52,  61 => 39,  94 => 38,  89 => 40,  85 => 25,  75 => 21,  68 => 6,  56 => 27,  196 => 90,  183 => 70,  171 => 93,  166 => 90,  163 => 70,  158 => 67,  156 => 65,  151 => 57,  142 => 59,  138 => 57,  136 => 56,  121 => 29,  117 => 47,  105 => 40,  91 => 31,  62 => 156,  49 => 23,  87 => 17,  21 => 11,  31 => 16,  25 => 13,  26 => 14,  28 => 14,  24 => 13,  19 => 11,  93 => 41,  88 => 6,  78 => 21,  46 => 21,  44 => 74,  27 => 14,  79 => 34,  72 => 54,  69 => 19,  47 => 22,  40 => 13,  37 => 23,  22 => 11,  246 => 32,  157 => 82,  145 => 46,  139 => 31,  131 => 25,  123 => 51,  120 => 21,  115 => 49,  111 => 45,  108 => 47,  101 => 43,  98 => 21,  96 => 31,  83 => 58,  74 => 31,  66 => 24,  55 => 24,  52 => 24,  50 => 75,  43 => 26,  41 => 25,  35 => 17,  32 => 14,  29 => 15,  209 => 82,  203 => 134,  199 => 67,  193 => 129,  189 => 71,  187 => 49,  182 => 66,  176 => 110,  173 => 94,  168 => 66,  164 => 84,  162 => 62,  154 => 54,  149 => 34,  147 => 33,  144 => 32,  141 => 61,  133 => 67,  130 => 66,  125 => 44,  122 => 22,  116 => 36,  112 => 48,  109 => 18,  106 => 61,  103 => 60,  99 => 30,  95 => 20,  92 => 19,  86 => 59,  82 => 35,  80 => 25,  73 => 7,  64 => 174,  60 => 16,  57 => 15,  54 => 144,  51 => 14,  48 => 64,  45 => 19,  42 => 61,  39 => 16,  36 => 5,  33 => 16,  30 => 15,);
    }
}
