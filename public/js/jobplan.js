    // Toggle between Translation and Revision buttons
    const translationBtn = document.getElementById('translationBtn');
    const revisionBtn = document.getElementById('revisionBtn');

    translationBtn.addEventListener('click', function() {
        translationBtn.classList.add('btn-active');
        revisionBtn.classList.remove('btn-active');
    });

    revisionBtn.addEventListener('click', function() {
        revisionBtn.classList.add('btn-active');
        translationBtn.classList.remove('btn-active');
    });