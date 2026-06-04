export const getCategories = async () => {
  const response = await fetch('core/views/scripts/getFormatedPostCategories.php');
  
  if (!response.ok) {
    throw new Error('Failed to fetch categories');
  }
  
  return response.json();
};
