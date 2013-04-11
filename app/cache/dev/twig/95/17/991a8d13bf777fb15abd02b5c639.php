<?php

/* SonataNewsBundle:Post:comments.html.twig */
class __TwigTemplate_9517991a8d13bf777fb15abd02b5c639 extends Twig_Template
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
        // line 13
        echo "
<div class=\"sonata-blog-comment-container\">
    <h3>";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("title_comments", array(), "SonataNewsBundle"), "html", null, true);
        echo "</h3>

    <div class=\"sonata-blog-comment-list\">
        ";
        // line 18
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "pager"), "results"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["comment"]) {
            // line 19
            echo "            <div class=\"sonata-blog-comment\">
                <div class=\"sonata-blog-comment-name\">
                    <a href=\"";
            // line 21
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "comment"), "url"), "html", null, true);
            echo "\" target=\"new\" rel=\"nofollow\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "comment"), "name"), "html", null, true);
            echo "</a>
                </div>
                <div class=\"sonata-blog-comment-date\">
                    ";
            // line 24
            echo $this->env->getExtension('sonata_intl_datetime')->formatDate($this->getAttribute($this->getContext($context, "comment"), "createdAt"));
            echo "
                </div>
                <div class=\"sonata-blog-comment-message\">
                    ";
            // line 27
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "comment"), "message"), "html", null, true);
            echo "
                </div>
            </div>
        ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 31
            echo "            ";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("no_comments_available", array(), "SonataNewsBundle"), "html", null, true);
            echo "
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['comment'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 33
        echo "    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "SonataNewsBundle:Post:comments.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 33,  61 => 31,  52 => 27,  46 => 24,  34 => 19,  29 => 18,  23 => 15,  19 => 13,  144 => 54,  138 => 51,  135 => 50,  132 => 49,  129 => 45,  127 => 44,  124 => 43,  122 => 42,  117 => 39,  114 => 38,  102 => 29,  94 => 23,  91 => 22,  81 => 15,  75 => 14,  68 => 9,  65 => 8,  60 => 6,  55 => 58,  53 => 38,  50 => 37,  48 => 22,  45 => 21,  43 => 8,  40 => 7,  38 => 21,  35 => 5,  32 => 4,);
    }
}
