import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { InfoCarPage } from './info-car.page';

const routes: Routes = [
  {
    path: '',
    component: InfoCarPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class InfoCarPageRoutingModule {}
