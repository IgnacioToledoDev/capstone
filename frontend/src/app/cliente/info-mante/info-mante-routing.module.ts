import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { InfoMantePage } from './info-mante.page';

const routes: Routes = [
  {
    path: '',
    component: InfoMantePage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class InfoMantePageRoutingModule {}
