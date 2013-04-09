<?php

/* SonataUserBundle:Admin/Core:user_block.html.twig */
class __TwigTemplate_f56beee6eeab78a4c1f572d11012ab38 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'user_block' => array($this, 'block_user_block'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('user_block', $context, $blocks);
    }

    public function block_user_block($context, array $blocks = array())
    {
        // line 2
        echo "    ";
        if ($this->getAttribute($this->getContext($context, "app"), "user")) {
            // line 3
            echo "        ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "app"), "user"), "html", null, true);
            echo "

        ";
            // line 5
            if (($this->env->getExtension('security')->isGranted("ROLE_PREVIOUS_ADMIN") && $this->getAttribute($this->getContext($context, "sonata_user"), "impersonating"))) {
                // line 6
                echo "            <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_user"), "impersonating"), "route"), twig_array_merge($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_user"), "impersonating"), "parameters"), array("_switch_user" => "_exit"))), "html", null, true);
                echo "\">(exit)</a>
        ";
            }
            // line 8
            echo "
        - <a href=\"";
            // line 9
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("sonata_user_admin_security_logout"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("user_block_logout", array(), "SonataUserBundle"), "html", null, true);
            echo "</a>
    ";
        }
    }

    public function getTemplateName()
    {
        return "SonataUserBundle:Admin/Core:user_block.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  46 => 9,  20 => 1,  675 => 217,  672 => 216,  667 => 209,  661 => 206,  655 => 203,  652 => 202,  650 => 201,  647 => 200,  641 => 198,  639 => 197,  636 => 196,  630 => 194,  628 => 193,  625 => 192,  619 => 190,  617 => 189,  614 => 188,  608 => 186,  606 => 185,  603 => 184,  600 => 183,  595 => 156,  591 => 153,  585 => 152,  576 => 149,  571 => 148,  566 => 147,  563 => 146,  558 => 145,  555 => 144,  549 => 110,  545 => 109,  542 => 108,  534 => 105,  528 => 104,  520 => 102,  517 => 101,  513 => 100,  508 => 98,  505 => 97,  500 => 96,  497 => 95,  494 => 94,  488 => 93,  481 => 87,  475 => 86,  470 => 85,  467 => 84,  461 => 48,  457 => 47,  453 => 46,  448 => 44,  443 => 42,  439 => 41,  434 => 40,  431 => 39,  425 => 36,  421 => 35,  415 => 32,  411 => 31,  406 => 29,  403 => 28,  400 => 27,  393 => 221,  391 => 216,  384 => 211,  382 => 183,  378 => 182,  375 => 181,  369 => 178,  366 => 177,  364 => 176,  360 => 174,  355 => 171,  352 => 170,  338 => 169,  332 => 167,  329 => 166,  311 => 165,  305 => 163,  303 => 162,  299 => 160,  297 => 159,  293 => 157,  291 => 156,  287 => 154,  285 => 144,  282 => 143,  278 => 141,  272 => 139,  269 => 138,  266 => 137,  252 => 136,  246 => 134,  241 => 131,  235 => 129,  227 => 127,  225 => 126,  222 => 125,  219 => 124,  201 => 123,  198 => 122,  196 => 121,  193 => 120,  191 => 119,  184 => 114,  179 => 111,  176 => 110,  173 => 94,  171 => 93,  166 => 90,  164 => 84,  157 => 82,  155 => 81,  143 => 71,  137 => 69,  133 => 67,  130 => 66,  127 => 65,  110 => 63,  106 => 61,  103 => 60,  86 => 59,  83 => 58,  80 => 57,  74 => 55,  72 => 54,  67 => 52,  61 => 39,  58 => 38,  56 => 27,  47 => 20,  43 => 8,  41 => 17,  35 => 5,  33 => 13,  31 => 12,  84 => 35,  76 => 30,  63 => 50,  55 => 15,  49 => 12,  45 => 19,  39 => 16,  37 => 6,  32 => 5,  29 => 3,  26 => 2,);
    }
}
