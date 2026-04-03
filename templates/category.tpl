{extends file='layouts/base.tpl'}

{block name="content"}
    <section class="category-header">
        <h1>{$category.name|escape}</h1>
        <p>{$category.description|escape}</p>
    </section>

    <section class="toolbar">
        <form method="get" class="sort-form">
            <label for="sort">Сортировка</label>
            <select id="sort" name="sort" onchange="this.form.submit()">
                <option value="date" {if $sortBy === 'date'}selected{/if}>По дате публикации</option>
                <option value="views" {if $sortBy === 'views'}selected{/if}>По просмотрам</option>
            </select>
        </form>
    </section>

    {if $posts|count === 0}
        <section class="empty-state">
            <h2>В этой категории пока нет статей</h2>
        </section>
    {else}
        <section class="cards-grid">
            {foreach from=$posts item=post}
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
        </section>

        <nav class="pagination" aria-label="Pagination">
            {if $pagination.hasPrev}
                <a href="/category/{$category.id}?sort={$sortBy|escape}&page={$pagination.prevPage}">← Назад</a>
            {/if}

            {foreach from=$pages item=pageItem}
                <a href="/category/{$category.id}?sort={$sortBy|escape}&page={$pageItem.number}"
                   class="{if $pageItem.isCurrent}active{/if}">
                    {$pageItem.number}
                </a>
            {/foreach}

            {if $pagination.hasNext}
                <a href="/category/{$category.id}?sort={$sortBy|escape}&page={$pagination.nextPage}">Вперёд →</a>
            {/if}
        </nav>
    {/if}
{/block}
