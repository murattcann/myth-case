<?php 
namespace App\Response;

class ProductResponse
{
    /**
     * @var string
     */
    private string $sku;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $category;

    /**
     * @var array
     */
    private array $price;

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of price
     *
     * @return  array
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param  array  $price
     *
     * @return  self
     */ 
    public function setPrice(array $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of category
     *
     * @return  string
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param  string  $category
     *
     * @return  self
     */ 
    public function setCategory(string $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of sku
     *
     * @return  string
     */ 
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set the value of sku
     *
     * @param  string  $sku
     *
     * @return  self
     */ 
    public function setSku(string $sku)
    {
        $this->sku = $sku;

        return $this;
    }

    public function toArray(){
        return (get_object_vars($this));
    }
}
