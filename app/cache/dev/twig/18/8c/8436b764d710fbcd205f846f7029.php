<?php

/* SonataNewsBundle:Post:view.html.twig */
class __TwigTemplate_188c8436b764d710fbcd205f846f7029 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::base.html.twig");

        $this->blocks = array(
            'corps' => array($this, 'block_corps'),
            'image_accueil' => array($this, 'block_image_accueil'),
            'titre_date' => array($this, 'block_titre_date'),
            'contenu' => array($this, 'block_contenu'),
            'commentaires' => array($this, 'block_commentaires'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_corps($context, array $blocks = array())
    {
        // line 5
        echo "
    ";
        // line 6
        $this->displayBlock('image_accueil', $context, $blocks);
        // line 7
        echo "
    ";
        // line 8
        $this->displayBlock('titre_date', $context, $blocks);
        // line 21
        echo "
    ";
        // line 22
        $this->displayBlock('contenu', $context, $blocks);
        // line 37
        echo "
    ";
        // line 38
        $this->displayBlock('commentaires', $context, $blocks);
        // line 58
        echo "
";
    }

    // line 6
    public function block_image_accueil($context, array $blocks = array())
    {
    }

    // line 8
    public function block_titre_date($context, array $blocks = array())
    {
        // line 9
        echo "
        <div class=\"row blocBlanc\">
            <div class=\"six columns\">
                <div class=\"row\">

                    <h2><a href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("sonata_news_view", array("permalink" => $this->env->getExtension('sonata_news')->generatePermalink($this->getContext($context, "post")))), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "post"), "title"), "html", null, true);
        echo "</a></h2>
                    ";
        // line 15
        echo $this->env->getExtension('sonata_intl_datetime')->formatDate($this->getAttribute($this->getContext($context, "post"), "publicationDateStart"));
        echo "
                </div>
            </div>
        </div>

    ";
    }

    // line 22
    public function block_contenu($context, array $blocks = array())
    {
        // line 23
        echo "
        &nbsp;

        <div class=\"row blocBlanc\">
            <div class=\"six columns\">
                <div class=\"row\">
                    ";
        // line 29
        echo $this->getAttribute($this->getContext($context, "post"), "content");
        echo "
                </div>
            </div>
        </div>

        &nbsp;

    ";
    }

    // line 38
    public function block_commentaires($context, array $blocks = array())
    {
        // line 39
        echo "        <div class=\"row blocBlanc\">
            <div class=\"six columns\">
                <div class=\"row\">
        ";
        // line 42
        echo $this->env->getExtension('actions')->renderAction("SonataNewsBundle:Post:comments", array("postId" => $this->getAttribute($this->getContext($context, "post"), "id")), array());
        // line 43
        echo "
        ";
        // line 44
        if ($this->getAttribute($this->getContext($context, "post"), "iscommentable")) {
            // line 45
            echo "            ";
            echo $this->env->getExtension('actions')->renderAction("SonataNewsBundle:Post:addCommentForm", array("postId" => $this->getAttribute($this->getContext($context, "post"), "id"), "form" => $this->getContext($context, "form")), array());
            // line 49
            echo "        ";
        } else {
            // line 50
            echo "
                ";
            // line 51
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("message_comments_are_closed", array(), "SonataNewsBundle"), "html", null, true);
            echo "

        ";
        }
        // line 54
        echo "                        </div>
            </div>
        </div>
    ";
    }

    public function getTemplateName()
    {
        return "SonataNewsBundle:Post:view.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  144 => 54,  138 => 51,  135 => 50,  132 => 49,  129 => 45,  127 => 44,  124 => 43,  122 => 42,  117 => 39,  114 => 38,  102 => 29,  94 => 23,  91 => 22,  81 => 15,  75 => 14,  68 => 9,  65 => 8,  60 => 6,  55 => 58,  53 => 38,  50 => 37,  48 => 22,  45 => 21,  43 => 8,  40 => 7,  38 => 6,  35 => 5,  32 => 4,);
    }
}
