document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.categories-section button');
    const products = document.querySelectorAll('#products .offer-item');
  
    buttons.forEach(button => {
      button.addEventListener('click', () => {
        const category = button.getAttribute('data-category');
        products.forEach(product => {
          product.classList.remove('hidden');
          if (product.getAttribute('data-category') !== category) {
            product.classList.add('hidden');
          }
        });
      });
    });
  });
  