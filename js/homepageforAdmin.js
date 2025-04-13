document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        item.addEventListener('click', function() {
            // Close all other FAQs
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                    const otherNumber = otherItem.querySelector('.faq-number');
                    otherNumber.classList.remove('faq-number-active');
                    otherNumber.classList.add('faq-number-inactive');
                }
            });

            // Toggle current FAQ
            this.classList.toggle('active');
            const number = this.querySelector('.faq-number');
            number.classList.toggle('faq-number-active');
            number.classList.toggle('faq-number-inactive');
        });
    });
});