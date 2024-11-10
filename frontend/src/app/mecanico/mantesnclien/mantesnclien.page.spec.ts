import { ComponentFixture, TestBed } from '@angular/core/testing';
import { MantesnclienPage } from './mantesnclien.page';

describe('MantesnclienPage', () => {
  let component: MantesnclienPage;
  let fixture: ComponentFixture<MantesnclienPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(MantesnclienPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
