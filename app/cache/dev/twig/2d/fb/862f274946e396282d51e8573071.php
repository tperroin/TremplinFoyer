<?php

/* SonataNewsBundle:Post:archive.html.twig */
class __TwigTemplate_2dfb862f274946e396282d51e8573071 extends Twig_Template
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
<h1>";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("title_archive", array(), "SonataNewsBundle"), "html", null, true);
        echo "</h1>

<div class=\"sonata-blog-post-list\">
    ";
        // line 17
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "pager"), "getResults", array(), "method"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 18
            echo "        <div class=\"sonata-blog-post-container\">
            <div class=\"sonata-blog-post-date-container\">
                ";
            // line 20
            echo $this->env->getExtension('sonata_intl_datetime')->formatDate($this->getAttribute($this->getContext($context, "post"), "publicationDateStart"));
            echo "
            </div>
            <h2 class=\"sonata-blog-post-title\">
                <a href=\"";
            // line 23
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("sonata_news_view", array("permalink" => $this->env->getExtension('sonata_news')->generatePermalink($this->getContext($context, "post")))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "post"), "title"), "html", null, true);
            echo "</a>
                <span class=\"sonata-blog-post-author\">";
            // line 24
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("archive_author", array("%author%" => $this->getAttribute($this->getContext($context, "post"), "author")), "SonataNewsBundle"), "html", null, true);
            echo "</span>
            </h2>
            <div class=\"sonata-blog-post-information\">

                <div class=\"sonata-blog-post-tag-container\">
                    <div class=\"sonata-blog-post-tag-title\">
                        ";
            // line 30
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->transchoice("published_under", twig_length_filter($this->env, $this->getAttribute($this->getContext($context, "post"), "tags")), array(), "SonataNewsBundle"), "html", null, true);
            echo "
                    </div>
                    <div class=\"sonata-blog-post-tag-list\">
                        ";
            // line 33
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "post"), "tags"));
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                // line 34
                echo "                            <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("sonata_news_tag", array("tag" => $this->getAttribute($this->getContext($context, "tag"), "slug"))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "tag"), "name"), "html", null, true);
                echo "</a>
                            ";
                // line 35
                if ((!$this->getAttribute($this->getContext($context, "loop"), "last"))) {
                    echo ", ";
                }
                // line 36
                echo "                        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 37
            echo "                    </div>
                </div>
            </div>

            <div class=\"sonata-blog-post-abtract\">
                ";
            // line 42
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "post"), "abstract"), "html", null, true);
            echo "
            </div>
            <span class=\"sonata-blog-post-comments-count\">";
            // line 44
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("comments_count", array("%comments_count%" => $this->getAttribute($this->getContext($context, "post"), "commentsCount")), "SonataNewsBundle"), "html", null, true);
            echo "</span>
        </div>

    ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 48
            echo "        ";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("no_post_found", array(), "SonataNewsBundle"), "html", null, true);
            echo "
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 50
        echo "
    <div class=\"sonata-blog-post-pagination\">
        ";
        // line 52
        if ($this->getAttribute($this->getContext($context, "pager"), "haveToPaginate", array(), "method")) {
            // line 53
            echo "            ";
            if (($this->getAttribute($this->getContext($context, "pager"), "page") != $this->getAttribute($this->getContext($context, "pager"), "previouspage"))) {
                // line 54
                echo "                <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl($this->getContext($context, "route"), twig_array_merge($this->getContext($context, "route_parameters"), array("page" => $this->getAttribute($this->getContext($context, "pager"), "previouspage")))), "html", null, true);
                echo "\" title=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_previous_page", array(), "SonataNewsBundle"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_previous_page", array(), "SonataNewsBundle"), "html", null, true);
                echo "</a>
            ";
            }
            // line 56
            echo "
            ";
            // line 57
            if (($this->getAttribute($this->getContext($context, "pager"), "page") != $this->getAttribute($this->getContext($context, "pager"), "nextpage"))) {
                // line 58
                echo "                <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl($this->getContext($context, "route"), twig_array_merge($this->getContext($context, "route_parameters"), array("page" => $this->getAttribute($this->getContext($context, "pager"), "nextpage")))), "html", null, true);
                echo "\" title=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_next_page", array(), "SonataNewsBundle"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_next_page", array(), "SonataNewsBundle"), "html", null, true);
                echo "</a>
            ";
            }
            // line 60
            echo "        ";
        }
        // line 61
        echo "    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "SonataNewsBundle:Post:archive.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  173 => 61,  170 => 60,  160 => 58,  158 => 57,  155 => 56,  145 => 54,  142 => 53,  140 => 52,  136 => 50,  127 => 48,  118 => 44,  113 => 42,  106 => 37,  92 => 36,  88 => 35,  81 => 34,  64 => 33,  58 => 30,  49 => 24,  43 => 23,  37 => 20,  33 => 18,  28 => 17,  22 => 14,  19 => 13,);
    }
}
