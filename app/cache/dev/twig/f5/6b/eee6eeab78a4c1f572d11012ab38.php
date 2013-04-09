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
        return array (  46 => 9,  20 => 1,  613 => 205,  610 => 204,  605 => 199,  598 => 195,  592 => 192,  588 => 190,  586 => 189,  583 => 188,  577 => 186,  575 => 185,  572 => 184,  566 => 182,  564 => 181,  561 => 180,  555 => 178,  553 => 177,  550 => 176,  544 => 174,  542 => 173,  539 => 172,  536 => 171,  531 => 159,  527 => 128,  521 => 127,  512 => 124,  507 => 123,  502 => 122,  499 => 121,  494 => 120,  491 => 119,  485 => 110,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  456 => 102,  453 => 101,  449 => 100,  444 => 98,  441 => 97,  436 => 96,  433 => 95,  430 => 94,  424 => 93,  417 => 87,  411 => 86,  406 => 85,  403 => 84,  397 => 48,  393 => 47,  389 => 46,  384 => 44,  379 => 42,  375 => 41,  370 => 40,  367 => 39,  361 => 36,  357 => 35,  351 => 32,  347 => 31,  342 => 29,  339 => 28,  336 => 27,  329 => 211,  327 => 204,  322 => 201,  320 => 171,  316 => 170,  313 => 169,  307 => 166,  304 => 165,  302 => 164,  296 => 160,  294 => 159,  289 => 156,  285 => 154,  279 => 152,  276 => 151,  273 => 150,  259 => 149,  253 => 147,  248 => 144,  242 => 142,  234 => 140,  232 => 139,  229 => 138,  226 => 137,  208 => 136,  205 => 135,  203 => 134,  200 => 133,  198 => 132,  193 => 129,  191 => 119,  184 => 114,  179 => 111,  176 => 110,  173 => 94,  171 => 93,  166 => 90,  164 => 84,  157 => 82,  155 => 81,  143 => 71,  137 => 69,  133 => 67,  130 => 66,  127 => 65,  110 => 63,  106 => 61,  103 => 60,  86 => 59,  83 => 58,  80 => 57,  74 => 55,  72 => 54,  67 => 52,  61 => 39,  58 => 38,  56 => 27,  47 => 20,  43 => 8,  41 => 17,  35 => 5,  33 => 13,  31 => 12,  84 => 35,  76 => 30,  63 => 50,  55 => 15,  49 => 12,  45 => 19,  39 => 16,  37 => 6,  32 => 5,  29 => 3,  26 => 2,);
    }
}
