document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('pre > code').forEach((block, i) => {
        const button = document.createElement('button');
        button.textContent = 'Copy';
        button.style.position = 'absolute';
        button.style.top = '5px';
        button.style.right = '5px';
        button.style.padding = '2px 8px';
        button.style.fontSize = '12px';
        button.style.cursor = 'pointer';
        button.style.borderRadius = '4px';
        button.style.border = '1px solid rgb(62 62 62)';
        button.style.color = '#cecece';

        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';

        const pre = block.parentElement;
        pre.parentElement.replaceChild(wrapper, pre);
        wrapper.appendChild(pre);
        wrapper.appendChild(button);

        button.addEventListener('click', () => {
            navigator.clipboard.writeText(block.innerText).then(() => {
                button.textContent = 'Copied!';
                setTimeout(() => button.textContent = 'Copy', 1000);
            });
        });
    });

    const links = document.querySelectorAll('.scrollspy-link');
    const headings = Array.from(links).map(link => document.querySelector(link.getAttribute('href')));
    console.log(headings);

    function onScroll() {
        const scrollTop = window.scrollY;
        let current = null;

        for (let i = 0; i < headings.length; i++) {
            if (headings[i].offsetTop - 120 <= scrollTop) {
                current = links[i];
            }
        }
        console.log(current);
        links.forEach(link => link.classList.remove('text-blue-600'));
        if (current) current.classList.add('text-blue-600');
    }

    window.addEventListener('scroll', onScroll);
    onScroll();
});
