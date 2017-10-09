function translatedSimpleMDE(id) {
    return new SimpleMDE({
        element: document.getElementById(id),
        spellChecker: false,
        forceSync: true,
        toolbar: [
            {
                name: "bold",
                action: SimpleMDE.toggleBold,
                className: "fa fa-bold",
                title: "Gras"
            },
            {
                name: "italic",
                action: SimpleMDE.toggleItalic,
                className: "fa fa-italic",
                title: "Italique"
            },
            "|",
            {
                name: "heading-1",
                action: SimpleMDE.toggleHeading1,
                className: "fa fa-header fa-header-x fa-header-1",
                title: "Titre 1"
            },
            {
                name: "heading-2",
                action: SimpleMDE.toggleHeading2,
                className: "fa fa-header fa-header-x fa-header-2",
                title: "Titre 2"
            },
            {
                name: "heading-3",
                action: SimpleMDE.toggleHeading3,
                className: "fa fa-header fa-header-x fa-header-3",
                title: "Titre 3"
            },
            "|",
            {
                name: "link",
                action: SimpleMDE.drawLink,
                className: "fa fa-link",
                title: "Créer un lien"
            },
            {
                name: "image",
                action: SimpleMDE.drawImage,
                className: "fa fa-picture-o",
                title: "Insérer une image"
            },
            "|",
            {
                name: "unordered-list",
                action: SimpleMDE.toggleUnorderedList,
                className: "fa fa-list-ul",
                title: "Liste à puce"
            },
            {
                name: "ordered-list",
                action: SimpleMDE.toggleOrderedList,
                className: "fa fa-list-ol",
                title: "Liste numérotée"
            },
            {
                name: "table",
                action: SimpleMDE.drawTable,
                className: "fa fa-table",
                title: "Tableau"
            },
            "|",
            {
                name: "preview",
                action: SimpleMDE.togglePreview,
                className: "fa fa-eye no-disable",
                title: "Preview"
            },
            "|",
            {
                name: "guide",
                action: "https://simplemde.com/markdown-guide",
                className: "fa fa-question-circle",
                title: "Guide de Markdown"
            }
        ],
        status: false
    });
}