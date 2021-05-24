import React from "react";
import { IconButton } from "@material-ui/core";
import StarIcon from "@material-ui/icons/Star";
import StarBorderIcon from "@material-ui/icons/StarBorder";

interface FavoriteButtonDto {
  editFavorite: (e: any) => void;
  favorite: boolean;
}

export const FavoriteButton: React.FC<FavoriteButtonDto> = ({
  favorite,
  editFavorite,
}) => {
  return (
    <IconButton onClick={editFavorite}>
      {favorite ? <StarIcon className="favorites" /> : <StarBorderIcon />}
    </IconButton>
  );
};
