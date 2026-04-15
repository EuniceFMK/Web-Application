using System;
using System.Collections.Generic;

namespace EFDemo01.ModelNWD;

public partial class ProductsAboveAveragePrice
{
    public string ProductName { get; set; } = null!;

    public decimal? UnitPrice { get; set; }
}
