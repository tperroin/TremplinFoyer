<?php

/* SonataAdminBundle:CRUD:edit_boolean.html.twig */
class __TwigTemplate_4aac3d2e128171134baa740f6814d918 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'field' => array($this, 'block_field'),
            'label' => array($this, 'block_label'),
            'errors' => array($this, 'block_errors'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 11
        echo "
<div>

    <div class=\"sonata-ba-field ";
        // line 14
        if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, "field_element"), "vars"), "errors")) > 0)) {
            echo "sonata-ba-field-error";
        }
        echo "\">
        ";
        // line 15
        $this->displayBlock('field', $context, $blocks);
        // line 16
        echo "        ";
        $this->displayBlock('label', $context, $blocks);
        // line 24
        echo "
        <div class=\"sonata-ba-field-error-messages\">
            ";
        // line 26
        $this->displayBlock('errors', $context, $blocks);
        // line 27
        echo "        </div>

    </div>
</div>
";
    }

    // line 15
    public function block_field($context, array $blocks = array())
    {
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "field_element"), 'widget');
    }

    // line 16
    public function block_label($context, array $blocks = array())
    {
        // line 17
        echo "            ";
        if ($this->getAttribute($this->getAttribute($this->getContext($context, "field_description", true), "options", array(), "any", false, true), "name", array(), "any", true, true)) {
            // line 18
            echo "                ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "field_element"), 'label', (twig_test_empty($_label_ = $this->getAttribute($this->getAttribute($this->getContext($context, "field_description"), "options"), "name")) ? array() : array("label" => $_label_)));
            echo "
            ";
        } else {
            // line 20
            echo "                ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "field_element"), 'label');
            echo "
            ";
        }
        // line 22
        echo "            <br />
        ";
    }

    // line 26
    public function block_errors($context, array $blocks = array())
    {
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "field_element"), 'errors');
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:CRUD:edit_boolean.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  140 => 55,  97 => 42,  679 => 218,  676 => 217,  671 => 210,  665 => 207,  656 => 203,  651 => 201,  645 => 199,  643 => 198,  640 => 197,  634 => 195,  632 => 194,  629 => 193,  623 => 191,  621 => 190,  618 => 189,  612 => 187,  610 => 186,  604 => 184,  599 => 157,  595 => 154,  589 => 153,  580 => 150,  570 => 148,  567 => 147,  559 => 145,  553 => 111,  549 => 110,  546 => 109,  532 => 105,  524 => 103,  504 => 97,  501 => 96,  492 => 94,  485 => 88,  474 => 86,  471 => 85,  465 => 49,  461 => 48,  457 => 47,  448 => 44,  431 => 39,  425 => 36,  415 => 32,  411 => 31,  403 => 28,  393 => 222,  384 => 212,  382 => 184,  369 => 179,  360 => 175,  355 => 172,  352 => 171,  338 => 170,  332 => 168,  305 => 164,  299 => 161,  291 => 157,  287 => 155,  285 => 145,  278 => 142,  266 => 138,  252 => 137,  241 => 132,  235 => 130,  227 => 128,  222 => 126,  193 => 121,  179 => 112,  166 => 91,  137 => 70,  86 => 60,  335 => 94,  326 => 90,  306 => 87,  303 => 163,  283 => 84,  279 => 82,  276 => 81,  273 => 80,  271 => 79,  268 => 78,  259 => 70,  255 => 69,  245 => 66,  218 => 57,  211 => 53,  206 => 51,  190 => 50,  187 => 49,  169 => 43,  167 => 42,  164 => 85,  134 => 26,  77 => 8,  65 => 5,  56 => 27,  53 => 77,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 204,  654 => 202,  642 => 193,  639 => 192,  636 => 191,  627 => 185,  624 => 184,  607 => 185,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 149,  572 => 175,  566 => 171,  562 => 146,  560 => 168,  555 => 167,  538 => 106,  521 => 102,  517 => 101,  512 => 99,  509 => 98,  506 => 160,  503 => 159,  500 => 158,  498 => 95,  495 => 156,  486 => 151,  482 => 149,  480 => 148,  477 => 147,  475 => 146,  472 => 145,  462 => 123,  453 => 46,  450 => 119,  437 => 138,  435 => 137,  432 => 136,  423 => 132,  421 => 35,  416 => 129,  405 => 127,  402 => 126,  400 => 27,  391 => 217,  377 => 111,  375 => 182,  371 => 109,  366 => 178,  356 => 105,  353 => 104,  343 => 98,  337 => 96,  331 => 92,  329 => 167,  324 => 92,  318 => 90,  312 => 89,  310 => 87,  302 => 86,  298 => 84,  289 => 81,  286 => 85,  274 => 77,  272 => 140,  269 => 139,  254 => 68,  250 => 67,  247 => 66,  243 => 65,  238 => 64,  236 => 63,  233 => 62,  208 => 57,  203 => 56,  200 => 55,  197 => 54,  175 => 45,  173 => 95,  112 => 48,  110 => 64,  90 => 18,  87 => 17,  69 => 29,  49 => 22,  23 => 12,  82 => 35,  62 => 156,  40 => 17,  20 => 11,  479 => 87,  473 => 161,  468 => 125,  460 => 155,  456 => 121,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 40,  429 => 144,  426 => 133,  422 => 142,  412 => 134,  408 => 132,  406 => 29,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 183,  373 => 116,  367 => 112,  364 => 177,  361 => 110,  359 => 106,  354 => 106,  340 => 97,  336 => 103,  321 => 91,  313 => 99,  311 => 166,  308 => 97,  304 => 95,  297 => 160,  293 => 158,  284 => 89,  282 => 144,  277 => 78,  267 => 85,  263 => 71,  257 => 81,  251 => 80,  246 => 135,  240 => 64,  234 => 74,  228 => 59,  223 => 58,  219 => 125,  213 => 69,  207 => 68,  198 => 123,  181 => 47,  176 => 111,  170 => 61,  168 => 60,  146 => 34,  142 => 56,  128 => 24,  125 => 44,  107 => 24,  38 => 24,  144 => 32,  141 => 51,  135 => 47,  126 => 45,  109 => 18,  103 => 61,  67 => 53,  61 => 17,  47 => 25,  105 => 24,  93 => 41,  76 => 22,  72 => 55,  68 => 6,  225 => 127,  216 => 90,  212 => 88,  205 => 84,  201 => 124,  196 => 122,  194 => 79,  191 => 120,  189 => 77,  186 => 76,  180 => 72,  172 => 44,  159 => 61,  154 => 59,  147 => 33,  132 => 48,  127 => 52,  121 => 29,  118 => 28,  114 => 42,  104 => 23,  100 => 34,  78 => 21,  75 => 23,  71 => 19,  58 => 16,  34 => 15,  27 => 14,  91 => 20,  84 => 16,  44 => 27,  25 => 3,  28 => 13,  24 => 12,  19 => 1,  94 => 39,  88 => 6,  79 => 34,  59 => 155,  31 => 14,  26 => 2,  21 => 2,  70 => 20,  63 => 27,  46 => 21,  22 => 11,  163 => 59,  155 => 82,  152 => 35,  149 => 34,  145 => 46,  139 => 31,  131 => 25,  123 => 51,  120 => 21,  115 => 49,  106 => 62,  101 => 43,  96 => 21,  83 => 59,  80 => 58,  74 => 31,  66 => 15,  55 => 24,  52 => 15,  50 => 75,  43 => 18,  41 => 19,  37 => 21,  35 => 16,  32 => 32,  29 => 13,  184 => 115,  178 => 46,  171 => 94,  165 => 58,  162 => 57,  157 => 83,  153 => 54,  151 => 53,  143 => 72,  138 => 51,  136 => 50,  133 => 68,  130 => 67,  122 => 22,  119 => 50,  116 => 35,  111 => 37,  108 => 47,  102 => 30,  98 => 21,  95 => 20,  92 => 19,  89 => 40,  85 => 25,  81 => 26,  73 => 7,  64 => 18,  60 => 26,  57 => 23,  54 => 144,  51 => 21,  48 => 64,  45 => 19,  42 => 26,  39 => 16,  36 => 7,  33 => 15,  30 => 15,);
    }
}
