<?php

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Slug(fields={"name", "description"})
     * @ORM\Column(length=100, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $wholesale_price;

    /**
     * @ORM\Column(type="float")
     */
    private $retail_price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="product")
     */
    private $images;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_available;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_visible;

    /**
     * @ORM\Column(type="boolean")
     */
    private $special_offer;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="products")
     */
    private $recommend_product;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="recommend_product")
     */
    private $products;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $manufacturer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $producing_country;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minimum_wholesale;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sale;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="products")
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $product_unit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Specification", mappedBy="product")
     */
    private $specifications;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->is_visible = true;
        $this->images = new ArrayCollection();
        $this->recommend_product = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->specifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }



    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getWholesalePrice(): ?float
    {
        return $this->wholesale_price;
    }

    public function setWholesalePrice(?float $wholesale_price): self
    {
        $this->wholesale_price = $wholesale_price;

        return $this;
    }

    public function getRetailPrice(): ?float
    {
        return $this->retail_price;
    }

    public function setRetailPrice(float $retail_price): self
    {
        $this->retail_price = $retail_price;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->is_available;
    }

    public function setIsAvailable(bool $is_available): self
    {
        $this->is_available = $is_available;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->is_visible;
    }

    public function setIsVisible(bool $is_visible): self
    {
        $this->is_visible = $is_visible;

        return $this;
    }

    public function getSpecialOffer(): ?bool
    {
        return $this->special_offer;
    }

    public function setSpecialOffer(bool $special_offer): self
    {
        $this->special_offer = $special_offer;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getRecommendProduct(): Collection
    {
        return $this->recommend_product;
    }

    public function addRecommendProduct(self $recommendProduct): self
    {
        if (!$this->recommend_product->contains($recommendProduct)) {
            $this->recommend_product[] = $recommendProduct;
        }

        return $this;
    }

    public function removeRecommendProduct(self $recommendProduct): self
    {
        if ($this->recommend_product->contains($recommendProduct)) {
            $this->recommend_product->removeElement($recommendProduct);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(self $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addRecommendProduct($this);
        }

        return $this;
    }

    public function removeProduct(self $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeRecommendProduct($this);
        }

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getProducingCountry(): ?string
    {
        return $this->producing_country;
    }

    public function setProducingCountry(?string $producing_country): self
    {
        $this->producing_country = $producing_country;

        return $this;
    }

    public function getMinimumWholesale(): ?int
    {
        return $this->minimum_wholesale;
    }

    public function setMinimumWholesale(?int $minimum_wholesale): self
    {
        $this->minimum_wholesale = $minimum_wholesale;

        return $this;
    }

    public function getSale(): ?float
    {
        return $this->sale;
    }

    public function setSale(?float $sale): self
    {
        $this->sale = $sale;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getProductUnit(): ?string
    {
        return $this->product_unit;
    }

    public function setProductUnit(?string $product_unit): self
    {
        $this->product_unit = $product_unit;

        return $this;
    }

    /**
     * @return Collection|Specification[]
     */
    public function getSpecifications(): Collection
    {
        return $this->specifications;
    }

    public function addSpecification(Specification $specification): self
    {
        if (!$this->specifications->contains($specification)) {
            $this->specifications[] = $specification;
            $specification->setProduct($this);
        }

        return $this;
    }

    public function removeSpecification(Specification $specification): self
    {
        if ($this->specifications->contains($specification)) {
            $this->specifications->removeElement($specification);
            // set the owning side to null (unless already changed)
            if ($specification->getProduct() === $this) {
                $specification->setProduct(null);
            }
        }

        return $this;
    }
}
