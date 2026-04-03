{extends file='layouts/base.tpl'}

{block name="content"}
    <article class="post-detail">
        <a class="back-link" href="/">← На главную</a>

        <img src="{$post.image|escape}" alt="{$post.title|escape}" class="post-hero-image">

        <h1>{$post.title|escape}</h1>
        <p class="card-meta">{$post.publishedAt|escape} · {$post.viewsCount} просмотров</p>

        {if $post.categories|count > 0}
            <div class="tag-list">
                {foreach from=$post.categories item=category}
                    <a href="/category/{$category.id}" class="tag">{$category.name|escape}</a>
                {/foreach}
            </div>
        {/if}

        <p class="lead">{$post.description|escape}</p>
        <div class="post-content">
            {$post.content|escape|nl2br}
        </div>
    </article>

    <section class="similar-section">
        <h2>Похожие статьи</h2>

        <div class="cards-grid">
            {foreach from=$similarPosts item=similar}
                <article class="post-card">
                    <a href="/post/{$similar.id}" class="card-image-wrap">
                        <img src="{$similar.image|escape}" alt="{$similar.title|escape}" class="card-image">
                    </a>
                    <h3><a href="/post/{$similar.id}">{$similar.title|escape}</a></h3>
                    <p class="card-meta">{$similar.publishedAt|escape} · {$similar.viewsCount} просмотров</p>
                    <p>{$similar.description|escape}</p>
                    <a href="/post/{$similar.id}" class="read-more">Continue Reading</a>
                </article>
            {/foreach}
        </div>
    </section>
{/block}
