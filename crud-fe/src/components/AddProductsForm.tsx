import React, { useState, ChangeEvent } from 'react';
import { useForm, SubmitHandler } from 'react-hook-form';

// Define an interface for the form's input fields
interface FormInputs {
  name: string;
  description: string;
  price: string;
  price_discount: string;
  purchase_price: string;
  stock: string;
  kode_product: string;
  thumbnail: FileList;
  images: FileList; // Allows multiple file uploads
}

// Interface for the server's response
interface ApiResponse {
  success: boolean;
  message: string;
}

const MultipleTextAndImageUploadForm: React.FC = () => {
  const { register, handleSubmit, formState: { errors } } = useForm<FormInputs>();
  const [imageFiles, setImageFiles] = useState<File[]>([]);
  const [thumbnailFiles, setThumbnailFiles] = useState<File[]>([]);
  const [submitStatus, setSubmitStatus] = useState<string | null>(null);

  const handleImageChange = (event: ChangeEvent<HTMLInputElement>) => {
    if (event.target.files) {
      setImageFiles(Array.from(event.target.files));
    }
  };
  const handleThumbnailChange = (event: ChangeEvent<HTMLInputElement>) => {
    if (event.target.files) {
      setThumbnailFiles(Array.from(event.target.files));
    }
  };

  const onSubmit: SubmitHandler<FormInputs> = async (data) => {
    const formData = new FormData();
    formData.append('name', data.name);
    formData.append('description', data.description);
    formData.append('price', data.price);
    formData.append('price_discount', data.price_discount);
    formData.append('purchase_price', data.purchase_price);
    formData.append('stock', data.stock);
    formData.append('kode_product', data.kode_product + new Date().getTime().toString());

    Array.from(data.thumbnail).forEach((file,i) => {
      formData.append(`thumbnail`, file);
    });

    Array.from(data.images).forEach((file,i) => {
      formData.append(`photos[${i}]`, file);
    });

    try {
      const response = await fetch('http://127.0.0.1:8000/api/products', {
        method: 'POST',
        body: formData,
      });

      if (response.ok) {
        const result: ApiResponse = await response.json();
        setSubmitStatus(`Success: ${result.message}`);
      } else {
        const errorText = await response.text(); // read error message from the response body
        setSubmitStatus(`Error: ${errorText}`);
      }
    } catch (error) {
      setSubmitStatus(`Fetch Error: ${(error as Error).message}`);
    }
  };

  return (
    <div>
      <h2>Form with Multiple Text Inputs and Image Uploads</h2>
      <form onSubmit={handleSubmit(onSubmit)}>
        <div>
          <label htmlFor="name">name</label>
          <input
            id="name"
            type="text"
            {...register('name', { required: 'name is required' })}
          />
          {errors.name && <span>{errors.name.message}</span>}
        </div>

        <div>
          <label htmlFor="description">Description</label>
          <textarea
            id="description"
            {...register('description', { required: 'Description is required' })}
          />
          {errors.description && <span>{errors.description.message}</span>}
        </div>

        <div>
          <label htmlFor="price">price</label>
          <input
          type="number"
            id="price"
            {...register('price', { required: 'Description is required' })}
          />
          {errors.description && <span>{errors.description.message}</span>}
        </div>
        <div>
          <label htmlFor="price_discount">price_discount</label>
          <input
          type="number"
            id="price_discount"
            {...register('price_discount', { required: 'Description is required' })}
          />
          {errors.description && <span>{errors.description.message}</span>}
        </div>
        <div>
          <label htmlFor="purchase_price">purchase_price</label>
          <input
          type="number"
            id="purchase_price"
            {...register('purchase_price', { required: 'Description is required' })}
          />
          {errors.description && <span>{errors.description.message}</span>}
        </div>
        <div>
          <label htmlFor="stock">stock</label>
          <input
            id="stock"
            type="number"
            {...register('stock', { required: 'Description is required' })}
          />
          {errors.description && <span>{errors.description.message}</span>}
        </div>
        <div>
          <label htmlFor="purchase_price">purchase_price</label>
          <input
            id="purchase_price"
            type="number"
            {...register('purchase_price', { required: 'Description is required' })}
          />
          {errors.description && <span>{errors.description.message}</span>}
        </div>
        <div>
          <label htmlFor="kode_product">kode_product</label>
          <input
            id="kode_product"
            type="text"
            {...register('kode_product', { required: 'Description is required' })}
          />
          {errors.description && <span>{errors.description.message}</span>}
        </div>

        <div>
          <label htmlFor="thumbnail">Upload thumbnail</label>
          <input
            id="thumbnail"
            type="file"
            accept="image/*"
            {...register('thumbnail', { required: 'Please upload at least one image' })}
            onChange={handleThumbnailChange}
          />
          {errors.images && <span>{errors.images.message}</span>}
        </div>

        <div>
          <label htmlFor="images">Upload Images</label>
          <input
            id="images"
            type="file"
            multiple
            accept="image/*"
            {...register('images', { required: 'Please upload at least one image' })}
            onChange={handleImageChange}
          />
          {errors.images && <span>{errors.images.message}</span>}
        </div>

        <button type="submit">Submit</button>
      </form>

      {submitStatus && <div>{submitStatus}</div>} {/* Display submission status */}
    </div>
  );
};

export default MultipleTextAndImageUploadForm;