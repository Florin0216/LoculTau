class PageCreateDto{
    title = null;
    section = null;
    content = null;

    constructor(page) {
        this.title = page.title;
        this.section = page.section;
        this.content = page.content;
    }
}

export default PageCreateDto;
