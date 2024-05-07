import React from 'react';
import AddProductsForm from '../components/AddProductsForm';

const AddProducts = () => {
  return (
    <div className='mx-4 my-10'>
        <h1 className='text-3xl mb-2 font-semibold'>Create new Product</h1>
        <AddProductsForm/>
    </div>
  );
};

export default AddProducts;
