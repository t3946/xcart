import React from "react";
import appData from "@admin/utils/app-data";
import AccordionPanel from "@admin/modules/common/components/accordion-panel/AccordionPanel";

const CorporationEditSections: React.FC<any> = (props: any) => {
  function headerTemplate() {
    const { currentSectionSlug, companyName, allSections } =
      appData().adminModule.corporationSections;
    const currentSectionTitle = allSections[currentSectionSlug].title;
    return (
      <h2 className="m-0 accordion-panel-header text-center">
        {`${currentSectionTitle}: ${companyName}`}
      </h2>
    );
  }

  function bodyTemplate() {
    const listItems = [];
    const sections = appData().adminModule.corporationSections.allSections;
    const currentSectionKey =
      appData().adminModule.corporationSections.currentSectionSlug;

    for (const sectionKey in sections) {
      const section = sections[sectionKey];
      let item = null;

      if (sectionKey === currentSectionKey) {
        item = <b className="section-link">{section.title}</b>;
      } else {
        item = (
          <a href={section.route} className="section-link">
            {section.title}
          </a>
        );
      }

      listItems.push(<li className="section-link-item">{item}</li>);
    }

    //разделить список ссылок на 2 колонки
    const firstColumnSize = Math.ceil(listItems.length / 2);
    const firstColumn = listItems.slice(0, firstColumnSize);
    const secondColumn = listItems.slice(firstColumnSize);

    return (
      <div className="accordion-panel-container">
        <div className="row">
          <div className="col-6">
            <ul className="list-unstyled distributor_section-links m-0">
              {firstColumn}
            </ul>
          </div>
          <div className="col-6">
            <ul className="list-unstyled distributor_section-links m-0">
              {secondColumn}
            </ul>
          </div>
        </div>
      </div>
    );
  }

  return <AccordionPanel header={headerTemplate()} body={bodyTemplate()} />;
};

export default CorporationEditSections;
