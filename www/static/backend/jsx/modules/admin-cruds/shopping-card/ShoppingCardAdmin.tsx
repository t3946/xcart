import React, { useEffect, useState } from "react";
import axios from "axios";
import {
  Accordion,
  AccordionDetails,
  AccordionSummary,
  Pagination,
  Stack,
  Typography,
} from "@mui/material";
import ExpandMoreIcon from "@mui/icons-material/ExpandMore";
import { CartItem } from "@admin/modules/admin-cruds/shopping-card/ts/types/cart.types";
import { LoadCartItems } from "@admin/modules/admin-cruds/shopping-card/components/load-cart-items/LoadCartItems";
import Button from "react-bootstrap/esm/Button";
import { Form } from "react-bootstrap";
import { useParams } from "react-router-dom";
export const ShoppingCardAdmin: React.FC = () => {
  const [page, setPage] = useState(1);
  const [items, setItems] = useState<CartItem[]>(null);
  const [maxPage, setMaxPage] = useState(null);
  const [search, setSearch] = useState("");
  const { cartId }: { cartId: string } = useParams();
  useEffect(() => {
    if (cartId) {
      setSearch(cartId);
      handleSubmit(cartId);
    } else {
      handleFetchItems(`/admin/cart/api/cart-items/${page}`);
    }
  }, [page]);
  const handleSubmit = (id = null) => {
    handleFetchItems(`/admin/cart/api/cart-item/${id ?? search}`);
  };
  const handleClearFilter = () => {
    setSearch("");
    setMaxPage(20);
    setPage(1);
    handleFetchItems(`/admin/cart/api/cart-items/${page}`);
  };
  const handleFetchItems = (url: string) => {
    setItems(null);
    axios.get(url).then((response) => {
      setItems(response.data.items);
      setMaxPage(response.data.maxPage);
    });
  };
  return (
    <Stack
      className="admin-custom-block"
      direction="column"
      spacing={2}
      justifyContent="center"
    >
      <div className="title-block">
        <span className="title">Shopping carts </span>
        <span className="title right" />
      </div>
      <Stack justifyContent="center">
        <Form.Group controlId="formBasicEmail">
          <Form.Label>Cart ID</Form.Label>
          <Form.Control
            type="number"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
        </Form.Group>
        <Stack
          direction="row"
          spacing={2}
          alignItems="center"
          justifyContent="center"
        >
          <Button
            disabled={!items}
            onClick={() => handleSubmit(null)}
            variant="warning"
          >
            Search
          </Button>
          <Button
            disabled={!items}
            onClick={handleClearFilter}
            variant="secondary"
          >
            Clear filter
          </Button>
        </Stack>
      </Stack>
      {items ? (
        items.map((item) => (
          <Accordion key={item.id}>
            <AccordionSummary
              expandIcon={<ExpandMoreIcon />}
              aria-controls="panel1a-content"
              id="panel1a-header"
            >
              <Typography>ID: {item.id}</Typography>
            </AccordionSummary>
            <AccordionDetails>
              <table
                className="custom-crud"
                border={1}
                style={{ borderCollapse: "collapse" }}
              >
                <thead>
                  <tr>
                    <th className="column-crud">Preview</th>
                    <th className="column-crud">SKU</th>
                    <th className="column-crud">Name</th>
                    <th className="column-crud">Price</th>
                    <th className="column-crud">Quantity</th>
                    <th className="column-crud">Total Price</th>
                  </tr>
                </thead>
                <tbody className="row-list">
                  {item.products.map((product) => (
                    <tr>
                      <td>
                        <img
                          width={60}
                          alt="Product photo"
                          src={product.image}
                        />
                      </td>
                      <td>
                        <a href={product.urlProduct}>{product.sku}</a>
                      </td>
                      <td>{product.name}</td>
                      <td>{product.price}</td>
                      <td>{product.quantity}</td>
                      <td>{product.totalPrice.toFixed(2)}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </AccordionDetails>
          </Accordion>
        ))
      ) : (
        <LoadCartItems />
      )}
      {maxPage && (
        <Stack alignItems="center" justifyContent="center">
          <Pagination
            disabled={!items}
            defaultValue={page}
            onChange={(e, page) => setPage(page)}
            count={maxPage}
          />
        </Stack>
      )}
    </Stack>
  );
};
