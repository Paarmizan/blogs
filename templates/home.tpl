{extends file='layouts/base.tpl'}

{block name="content"}
    {if $sections|count === 0}
        <section class="empty-state">
            <h1>Пока нет опубликованных статей</h1>
            <p>Запустите сидер, чтобы заполнить блог тестовыми данными.</p>
        </section>
    {else}
        {foreach from=$sections item=section}
            <section class="category-block">
                <div class="section-head">
                    <h2>{$section.category.name|escape}</h2>
                    <a class="view-all" href="/category/{$section.category.id}">Все статьи</a>
                </div>
                <p class="section-description">{$section.category.description|escape}</p>

                <div class="cards-grid">
                    {foreach from=$section.posts item=post}
                        <article class="post-card">
                            <a href="/post/{$post.id}" class="card-image-wrap">
                                <img src="{$post.image|escape}" alt="{$post.title|escape}" class="card-image">
                            </a>
                            <h3><a href="/post/{$post.id}">{$post.title|escape}</a></h3>
                            <p class="card-meta">{$post.publishedAt|escape} · {$post.viewsCount} просмотров</p>
                            <p>{$post.description|escape}</p>
                            <a href="/post/{$post.id}" class="read-more">Continue Reading</a>
                        </article>
                    {/foreach}
                </div>
            </section>
        {/foreach}
    {/if}
{/block}
