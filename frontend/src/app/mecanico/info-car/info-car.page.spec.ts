import { ComponentFixture, TestBed } from '@angular/core/testing';
import { InfoCarPage } from './info-car.page';

describe('InfoCarPage', () => {
  let component: InfoCarPage;
  let fixture: ComponentFixture<InfoCarPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(InfoCarPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
