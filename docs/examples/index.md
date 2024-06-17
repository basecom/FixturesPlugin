<script setup>
    import { data } from './index.data.js'
    import { computed } from 'vue';

    const examples = computed(() => {
        return data.filter(item => item.frontmatter.example);
    });
</script>

# Examples

In the following pages, you'll find a few examples of how to build fixtures. If you need a specific example, feel free to [create a discussion on github](https://github.com/basecom/FixturesPlugin/discussions):

<ul>
    <li v-for="item in examples">
        <a :href="'/FixturesPlugin' + item.url">{{item.frontmatter.example}}</a>
    </li>
</ul>